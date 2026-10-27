/* Browser regressions on real WordPress pages, including narrow mobile viewports. */
const assert = require('node:assert/strict');
const fs = require('node:fs');
const { chromium } = require(process.env.ATORA_PLAYWRIGHT_MODULE || 'playwright');
const base = process.env.ATORA_BASE_URL || 'http://127.0.0.1:8080';
const out = process.env.ATORA_UI_OUTPUT || '/tmp/atora-ui';
fs.mkdirSync(out, { recursive: true });
(async () => {
 const browser = await chromium.launch({ headless: true });
 try {
  // External video/avatar providers are not dependencies of a layout regression.
  const isolate = async context => {
   context.setDefaultTimeout(15000);
   context.setDefaultNavigationTimeout(20000);
   await context.route('**/*', route => {
    const url=new URL(route.request().url());
    return url.origin===new URL(base).origin || !['http:','https:'].includes(url.protocol) ? route.continue() : route.abort();
   });
  };
  const page = await browser.newPage({ viewport: { width: 390, height: 844 } });
  await isolate(page.context());
  await page.goto(base + '/', { waitUntil: 'domcontentloaded' });
  const toggle = page.locator('[data-mobile-toggle]');
  await toggle.click();
  assert.equal(await toggle.getAttribute('aria-expanded'), 'true');
  const mobileSearch = page.locator('[data-navigation] [data-search-toggle]');
  await mobileSearch.click();
  await page.waitForTimeout(100);
  assert.equal(await page.locator('[data-search-panel]').isVisible(), true);
  assert.equal(await page.locator('input[type=search]').first().evaluate(el => el === document.activeElement), true);
  await page.keyboard.press('Escape');
  assert.equal(await toggle.evaluate(el => el === document.activeElement), true, 'Search returns focus to the visible menu control');
  await toggle.click();
  await page.setViewportSize({ width: 1280, height: 900 });
  await page.waitForTimeout(100);
  assert.equal(await page.locator('body').evaluate(el => el.classList.contains('meridian-lock-scroll')), false);
  await page.setViewportSize({ width: 390, height: 600 });
  await toggle.click();
  await page.locator('[data-navigation] .meridian-menu').evaluate(el => {
   for (let i=0; i<20; i++) { const li=document.createElement('li'); li.innerHTML='<a href="#long-menu">Menu item</a>';el.appendChild(li); }
  });
  assert.equal(await page.locator('[data-navigation]').evaluate(el => {el.scrollTop=300;return el.scrollTop>0;}), true, 'Long mobile menu scrolls independently');
  await page.keyboard.press('Escape');
  for (const [role, cookie, paths] of [
   ['guest', '', ['/', '/verificar/', '/eliminar-cuenta/']],
   ['student', process.env.ATORA_STUDENT_COOKIE, ['/panel-estudiante/', '/leccion/leccion-e2e-videos/']],
   ['teacher', process.env.ATORA_TEACHER_COOKIE, ['/panel-docente/']]
  ]) {
   assert(role==='guest' || cookie, 'Authenticated test cookie required for '+role);
   const context = await browser.newContext();
   await isolate(context);
   if (cookie) { const i=cookie.indexOf('=');await context.addCookies([{name:cookie.slice(0,i),value:cookie.slice(i+1),url:base}]); }
   const screen = await context.newPage();
   for (const width of [375, 390, 768, 1280]) {
    await screen.setViewportSize({ width, height: 900 });
    for (const path of paths) {
     console.log('CHECK',role,width,path);
     const response=await screen.goto(base+path,{waitUntil:'domcontentloaded'});
     assert.equal(response.status(),200, role+' '+path);
     await screen.waitForTimeout(150);
     assert.equal(await screen.evaluate(()=>document.documentElement.scrollWidth<=window.innerWidth+2),true,`${role} ${path}: horizontal overflow at ${width}px`);
     if(path.includes('panel-')) assert.equal(await screen.locator(role==='teacher'?'.clms-td':'.clms-sd').count(),1, 'Authenticated dashboard rendered');
     await screen.screenshot({path:`${out}/${role}-${width}-${path.replace(/[^a-z0-9]/gi,'_')}.png`,fullPage:true,timeout:15000,animations:'disabled'});
    }
   }
   await context.close();
  }
  for (const mode of ['blocked-script','no-observer','broken-observer']) {
   const context=await browser.newContext();
   await isolate(context);
   const screen=await context.newPage();
   if(mode==='blocked-script') await screen.route('**/assets/js/main.js*',route=>route.abort());
   if(mode==='no-observer') await screen.addInitScript(()=>{window.IntersectionObserver=undefined;});
   if(mode==='broken-observer') await screen.addInitScript(()=>{window.IntersectionObserver=class{constructor(){throw Error('unavailable');}};});
   await screen.goto(base+'/',{waitUntil:'domcontentloaded'});
   assert((await screen.locator('[data-reveal]').count())>0,'Exercise actual animated content');
   assert.equal(await screen.locator('[data-reveal]').evaluateAll(els=>els.every(el=>getComputedStyle(el).opacity!=='0')),true,mode+': content stays visible');
   await context.close();
  }
  const nojs=await browser.newContext({javaScriptEnabled:false,viewport:{width:390,height:844}});
  await isolate(nojs);
  const screen=await nojs.newPage();await screen.goto(base+'/');
  assert.equal(await screen.locator('[data-navigation]').isVisible(),true,'Mobile navigation remains usable without JavaScript');
  await nojs.close();
  console.log('PASS: mobile navigation/search/focus, resize, long menus, public pages, student/teacher viewports and JavaScript failures.');
 } finally { await browser.close(); }
})().catch(error => { console.error(error); process.exit(1); });

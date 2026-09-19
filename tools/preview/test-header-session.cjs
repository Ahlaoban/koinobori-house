const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const source = fs.readFileSync(require('node:path').join(__dirname, '../../wp/themes/koinobori-child/assets/js/header.js'), 'utf8');
const values = new Map();
const storage = { getItem: key => values.get(key) ?? null, setItem: (key, value) => values.set(key, value) };
function page(sessionStorage) {
  const context = vm.createContext({ window: { sessionStorage }, document: { getElementById: () => null } });
  vm.runInContext(source, context);
  return context.khHeaderClaimFirstVisit;
}
assert.equal(page(storage)(), true, 'First visit claims intro');
assert.equal(page(storage)(), false, 'Navigation to another page never repeats intro');
assert.equal(page(storage)(), false, 'Reload or language change never repeats intro');
assert.equal(page({ getItem: () => null, setItem: () => {} })(), true, 'New session may introduce header');
assert.equal(page({ getItem() { throw Error('blocked'); } })(), false, 'Blocked storage never repeats intro');
assert.equal(page({ getItem: () => null, setItem() { throw Error('quota'); } })(), false, 'Failed persistence skips intro');
console.log('PASS: 6 header session checks');

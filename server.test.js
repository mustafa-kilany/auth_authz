const test=require('node:test');const assert=require('node:assert/strict');const {users}=require('./server');
test('defines admin and viewer roles',()=>{assert.equal(users.admin.role,'admin');assert.equal(users.viewer.role,'viewer')});
test('demo accounts use no real credentials',()=>assert.equal(users.admin.password,'demo'));

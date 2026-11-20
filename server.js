const http=require('node:http');
const users={admin:{password:'demo',role:'admin'},viewer:{password:'demo',role:'viewer'}};
function json(res,status,data){res.writeHead(status,{'Content-Type':'application/json'});res.end(JSON.stringify(data))}
async function body(req){let s='';for await(const c of req)s+=c;return JSON.parse(s||'{}')}
const server=http.createServer(async(req,res)=>{
 const u=new URL(req.url,'http://localhost');
 if(req.method==='GET'&&u.pathname==='/' )return json(res,200,{name:'RBAC Auth Demo',accounts:['admin/demo','viewer/demo']});
 if(req.method==='POST'&&u.pathname==='api/login'){try{const b=await body(req);const user=users[b.username];if(!user||user.password!==b.password)return json(res,401,{error:'Invalid credentials'});return json(res,200,{token:`demo-${b.username}`,role:user.role})}catch{return json(res,400,{error:'Invalid JSON'})}}
 if(req.method==='GET'&&u.pathname==='api/profile')return json(res,200,{role:String(req.headers['x-demo-role']||'viewer'),permissions:['read']});
 if(req.method==='GET'&&u.pathname==='api/admin/users'){if(req.headers['x-demo-role']!== 'admin')return json(res,403,{error:'Admin role required'});return json(res,200,{users:Object.keys(users).map(username=>({username,role:users[username].role}))})}
 json(res,404,{error:'Not found'});
});
if(require.main===module)server.listen(process.env.PORT||4003,()=>console.log('RBAC demo running'));
module.exports={server,users};

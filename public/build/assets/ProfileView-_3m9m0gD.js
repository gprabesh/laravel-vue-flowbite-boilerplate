import{F as g,G as S,v as U,o as v,c as N,w as a,a as e,H as k,I as h,h as n,i as p,E as j,J as C,u as i,K as M,j as f,t as b,_ as P,b as R,B as V,d,e as A,y as x,z as y,A as c,C as B,L as F,D,M as $,n as E}from"./main-CSHGpLCA.js";import{a as G,_ as u}from"./FormField-CYB-ksh_.js";import{_ as H}from"./FormFilePicker-EXopDD2l.js";const Y={class:"space-y-3 text-center md:text-left lg:mx-12"},L={class:"flex justify-center md:block"},O={class:"text-2xl"},T={class:"flex justify-center md:block"},z={__name:"UserCard",setup(q){const m=g(),r=S(()=>m.userName),s=U(!1);return(_,o)=>(v(),N(f,null,{default:a(()=>[e(k,{type:"justify-around lg:justify-center"},{default:a(()=>[e(h,{class:"lg:mx-12"}),n("div",Y,[n("div",L,[e(G,{modelValue:s.value,"onUpdate:modelValue":o[0]||(o[0]=w=>s.value=w),name:"notifications-switch",type:"switch",label:"Notifications","input-value":!0},null,8,["modelValue"])]),n("h1",O,[o[1]||(o[1]=p(" Howdy, ")),n("b",null,j(r.value),1),o[2]||(o[2]=p("! "))]),o[3]||(o[3]=n("p",null,[p("Last login "),n("b",null,"12 mins ago"),p(" from "),n("b",null,"127.0.0.1")],-1)),n("div",T,[e(C,{label:"Verified",color:"info",icon:i(M)},null,8,["icon"])])])]),_:1})]),_:1}))}},I={class:"grid grid-cols-1 lg:grid-cols-2 gap-6"},W={__name:"ProfileView",setup(q){const m=g(),r=b({name:m.userName,email:m.userEmail}),s=b({password_current:"",password:"",password_confirmation:""}),_=()=>{m.setUser(r)},o=()=>{};return(w,l)=>(v(),N(P,null,{default:a(()=>[e(E,null,{default:a(()=>[e(R,{icon:i(V),title:"Profile",main:""},{default:a(()=>[e(d,{href:"https://github.com/justboil/admin-one-vue-tailwind",target:"_blank",icon:i(A),label:"Star on GitHub",color:"contrast","rounded-full":"",small:""},null,8,["icon"])]),_:1},8,["icon"]),e(z,{class:"mb-6"}),n("div",I,[e(f,{"is-form":"",onSubmit:x(_,["prevent"])},{footer:a(()=>[e(y,null,{default:a(()=>[e(d,{color:"info",type:"submit",label:"Submit"}),e(d,{color:"info",label:"Options",outline:""})]),_:1})]),default:a(()=>[e(u,{label:"Avatar",help:"Max 500kb"},{default:a(()=>[e(H,{label:"Upload"})]),_:1}),e(u,{label:"Name",help:"Required. Your name"},{default:a(()=>[e(c,{modelValue:r.name,"onUpdate:modelValue":l[0]||(l[0]=t=>r.name=t),icon:i(V),name:"username",required:"",autocomplete:"username"},null,8,["modelValue","icon"])]),_:1}),e(u,{label:"E-mail",help:"Required. Your e-mail"},{default:a(()=>[e(c,{modelValue:r.email,"onUpdate:modelValue":l[1]||(l[1]=t=>r.email=t),icon:i(B),type:"email",name:"email",required:"",autocomplete:"email"},null,8,["modelValue","icon"])]),_:1})]),_:1}),e(f,{"is-form":"",onSubmit:x(o,["prevent"])},{footer:a(()=>[e(y,null,{default:a(()=>[e(d,{type:"submit",color:"info",label:"Submit"}),e(d,{color:"info",label:"Options",outline:""})]),_:1})]),default:a(()=>[e(u,{label:"Current password",help:"Required. Your current password"},{default:a(()=>[e(c,{modelValue:s.password_current,"onUpdate:modelValue":l[2]||(l[2]=t=>s.password_current=t),icon:i(F),name:"password_current",type:"password",required:"",autocomplete:"current-password"},null,8,["modelValue","icon"])]),_:1}),e(D),e(u,{label:"New password",help:"Required. New password"},{default:a(()=>[e(c,{modelValue:s.password,"onUpdate:modelValue":l[3]||(l[3]=t=>s.password=t),icon:i($),name:"password",type:"password",required:"",autocomplete:"new-password"},null,8,["modelValue","icon"])]),_:1}),e(u,{label:"Confirm password",help:"Required. New password one more time"},{default:a(()=>[e(c,{modelValue:s.password_confirmation,"onUpdate:modelValue":l[4]||(l[4]=t=>s.password_confirmation=t),icon:i($),name:"password_confirmation",type:"password",required:"",autocomplete:"new-password"},null,8,["modelValue","icon"])]),_:1})]),_:1})])]),_:1})]),_:1}))}};export{W as default};
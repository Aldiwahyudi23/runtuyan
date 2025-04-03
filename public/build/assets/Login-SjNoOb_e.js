import{C as _,d,o as i,b as t,u as o,m as b,w as m,e as u,a,t as x,h as k,c as y,P as V,f,n as v,F as $}from"./app-CI7z45tB.js";import{A as h}from"./AuthenticationCard-w9yg4aT7.js";import{_ as B}from"./AuthenticationCardLogo-Dwscd14o.js";import{_ as C}from"./Checkbox-CpY46k5O.js";import{_ as c,a as p}from"./TextInput-CafRrjEZ.js";import{_ as g}from"./InputLabel-02-f10Lf.js";import{_ as P}from"./PrimaryButton-QPVVS8tF.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const F={key:0,class:"mb-4 font-medium text-sm text-green-600"},N={class:"mt-4"},q={class:"block mt-4"},L={class:"flex items-center"},R={class:"flex items-center justify-end mt-4"},T={__name:"Login",props:{canResetPassword:Boolean,status:String},setup(l){const e=_({email:"",password:"",remember:!1}),w=()=>{e.transform(n=>({...n,remember:e.remember?"on":""})).post(route("login"),{onFinish:()=>e.reset("password")})};return(n,s)=>(i(),d($,null,[t(o(b),{title:"Log in"}),t(h,null,{logo:m(()=>[t(B)]),default:m(()=>[l.status?(i(),d("div",F,x(l.status),1)):u("",!0),a("form",{onSubmit:k(w,["prevent"])},[a("div",null,[t(g,{for:"email",value:"Email"}),t(c,{id:"email",modelValue:o(e).email,"onUpdate:modelValue":s[0]||(s[0]=r=>o(e).email=r),type:"email",class:"mt-1 block w-full",required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),t(p,{class:"mt-2",message:o(e).errors.email},null,8,["message"])]),a("div",N,[t(g,{for:"password",value:"Password"}),t(c,{id:"password",modelValue:o(e).password,"onUpdate:modelValue":s[1]||(s[1]=r=>o(e).password=r),type:"password",class:"mt-1 block w-full",required:"",autocomplete:"current-password"},null,8,["modelValue"]),t(p,{class:"mt-2",message:o(e).errors.password},null,8,["message"])]),a("div",q,[a("label",L,[t(C,{checked:o(e).remember,"onUpdate:checked":s[2]||(s[2]=r=>o(e).remember=r),name:"remember"},null,8,["checked"]),s[3]||(s[3]=a("span",{class:"ms-2 text-sm text-gray-600"},"Remember me",-1))])]),a("div",R,[l.canResetPassword?(i(),y(o(V),{key:0,href:n.route("password.request"),class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:m(()=>s[4]||(s[4]=[f(" Forgot your password? ")])),_:1},8,["href"])):u("",!0),t(P,{class:v(["ms-4",{"opacity-25":o(e).processing}]),disabled:o(e).processing},{default:m(()=>s[5]||(s[5]=[f(" Log in ")])),_:1},8,["class","disabled"])])],32)]),_:1})],64))}};export{T as default};

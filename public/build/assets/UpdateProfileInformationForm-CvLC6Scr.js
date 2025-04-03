import{C as j,r as f,c as w,o as c,w as l,b as t,u as s,f as n,n as B,d as S,e as p,a as r,j as v,E as g,G as E,h,P as z,W as A}from"./app-CI7z45tB.js";import{_ as F}from"./ActionMessage-B7sG3gRP.js";import{_ as R}from"./FormSection-CppuGSDU.js";import{a as _,_ as V}from"./TextInput-CafRrjEZ.js";import{_ as k}from"./InputLabel-02-f10Lf.js";import{_ as q}from"./PrimaryButton-QPVVS8tF.js";import{_ as C}from"./SecondaryButton-DBYCy7bv.js";import"./SectionTitle-TRV3i8LY.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const D={key:0,class:"col-span-6 sm:col-span-4"},L={class:"mt-2"},T=["src","alt"],W={class:"mt-2"},G={class:"col-span-6 sm:col-span-4"},M={class:"col-span-6 sm:col-span-4"},O={key:0},Y={class:"text-sm mt-2"},H={class:"mt-2 font-medium text-sm text-green-600"},ae={__name:"UpdateProfileInformationForm",props:{user:Object},setup(u){const y=u,o=j({_method:"PUT",name:y.user.name,email:y.user.email,photo:null}),P=f(null),m=f(null),i=f(null),$=()=>{i.value&&(o.photo=i.value.files[0]),o.post(route("user-profile-information.update"),{errorBag:"updateProfileInformation",preserveScroll:!0,onSuccess:()=>b()})},x=()=>{P.value=!0},I=()=>{i.value.click()},N=()=>{const a=i.value.files[0];if(!a)return;const e=new FileReader;e.onload=d=>{m.value=d.target.result},e.readAsDataURL(a)},U=()=>{A.delete(route("current-user-photo.destroy"),{preserveScroll:!0,onSuccess:()=>{m.value=null,b()}})},b=()=>{var a;(a=i.value)!=null&&a.value&&(i.value.value=null)};return(a,e)=>(c(),w(R,{onSubmitted:$},{title:l(()=>e[2]||(e[2]=[n(" Profile Information ")])),description:l(()=>e[3]||(e[3]=[n(" Update your account's profile information and email address. ")])),form:l(()=>[a.$page.props.jetstream.managesProfilePhotos?(c(),S("div",D,[r("input",{id:"photo",ref_key:"photoInput",ref:i,type:"file",class:"hidden",onChange:N},null,544),t(k,{for:"photo",value:"Photo"}),v(r("div",L,[r("img",{src:u.user.profile_photo_url,alt:u.user.name,class:"rounded-full size-20 object-cover"},null,8,T)],512),[[g,!m.value]]),v(r("div",W,[r("span",{class:"block rounded-full size-20 bg-cover bg-no-repeat bg-center",style:E("background-image: url('"+m.value+"');")},null,4)],512),[[g,m.value]]),t(C,{class:"mt-2 me-2",type:"button",onClick:h(I,["prevent"])},{default:l(()=>e[4]||(e[4]=[n(" Select A New Photo ")])),_:1}),u.user.profile_photo_path?(c(),w(C,{key:0,type:"button",class:"mt-2",onClick:h(U,["prevent"])},{default:l(()=>e[5]||(e[5]=[n(" Remove Photo ")])),_:1})):p("",!0),t(_,{message:s(o).errors.photo,class:"mt-2"},null,8,["message"])])):p("",!0),r("div",G,[t(k,{for:"name",value:"Name"}),t(V,{id:"name",modelValue:s(o).name,"onUpdate:modelValue":e[0]||(e[0]=d=>s(o).name=d),type:"text",class:"mt-1 block w-full",required:"",autocomplete:"name"},null,8,["modelValue"]),t(_,{message:s(o).errors.name,class:"mt-2"},null,8,["message"])]),r("div",M,[t(k,{for:"email",value:"Email"}),t(V,{id:"email",modelValue:s(o).email,"onUpdate:modelValue":e[1]||(e[1]=d=>s(o).email=d),type:"email",class:"mt-1 block w-full",required:"",autocomplete:"username"},null,8,["modelValue"]),t(_,{message:s(o).errors.email,class:"mt-2"},null,8,["message"]),a.$page.props.jetstream.hasEmailVerification&&u.user.email_verified_at===null?(c(),S("div",O,[r("p",Y,[e[7]||(e[7]=n(" Your email address is unverified. ")),t(s(z),{href:a.route("verification.send"),method:"post",as:"button",class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500",onClick:h(x,["prevent"])},{default:l(()=>e[6]||(e[6]=[n(" Click here to re-send the verification email. ")])),_:1},8,["href"])]),v(r("div",H," A new verification link has been sent to your email address. ",512),[[g,P.value]])])):p("",!0)])]),actions:l(()=>[t(F,{on:s(o).recentlySuccessful,class:"me-3"},{default:l(()=>e[8]||(e[8]=[n(" Saved. ")])),_:1},8,["on"]),t(q,{class:B({"opacity-25":s(o).processing}),disabled:s(o).processing},{default:l(()=>e[9]||(e[9]=[n(" Save ")])),_:1},8,["class","disabled"])]),_:1}))}};export{ae as default};

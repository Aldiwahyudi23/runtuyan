import{r as B,i as w,d as r,o as l,b as c,w as x,a as e,n as N,t as u,f as v,u as $,P as M,e as f,F as b,g as y,c as j,m as A}from"./app-CKb0f9o2.js";import{_ as F}from"./AppLayout-BFinnRwq.js";import{_ as D}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{p as K,d as O,c as z}from"./ageCalculator-B2gtlMSA.js";import{f as R}from"./format-CkpID6nj.js";const q={class:"member-info-container"},V={class:"member-info-content"},H={class:"member-name"},E={class:"member-details"},G={__name:"FamilyTreeNode",props:{person:Object,isMain:{type:Boolean,default:!1}},emits:["member-selected"],setup(n,{emit:g}){const m=n,p=g,s=B(!1),d=B(!1),T=()=>{s.value=!s.value,p("member-selected",{person:m.person,selected:s.value})},I=w(()=>m.person.name.split(" ").map(h=>h[0]).join("").toUpperCase()),S=w(()=>m.person.gender==="male"?"Laki-laki":"Perempuan"),L=w(()=>{if(!m.person.birth_date)return"-";const _=K(m.person.birth_date),h=m.person.death_date?K(m.person.death_date):new Date,a=O(h,_);return m.person.death_date?`${a} tahun (Alm)`:`${a} tahun`}),C=w(()=>({"bg-blue-100 text-blue-800":m.person.gender==="male","bg-pink-100 text-pink-800":m.person.gender==="female","border-2 border-yellow-400":m.isMain,"ring-2 ring-blue-500":s.value,"scale-110":d.value&&!s.value}));return(_,h)=>(l(),r("div",{class:N(["family-member",{"main-member":n.isMain,selected:s.value}]),onClick:T,onMouseenter:h[0]||(h[0]=a=>d.value=!0),onMouseleave:h[1]||(h[1]=a=>d.value=!1)},[c($(M),{href:_.route("people.show",n.person.id),class:"member-card"},{default:x(()=>[e("div",{class:N(["member-avatar",C.value])},u(I.value),3),e("div",q,[e("div",V,[e("div",H,u(n.person.name),1),e("div",E,[v(u(S.value)+" ",1),h[2]||(h[2]=e("br",null,null,-1)),v(" "+u(L.value),1)])])])]),_:1},8,["href"])],34))}},k=D(G,[["__scopeId","data-v-0d96ee20"]]),U={class:"family-tree"},J={key:0,class:"tree-container"},Y={key:0,class:"parents-level"},Q={class:"parents-wrapper"},W={class:"parents-container"},X={class:"relationship-label"},Z={key:0,class:"spouses-of-parent"},ee={class:"relationship-label"},se={class:"main-row"},ne={class:"main-person"},te={class:"relationship-label main-label"},le={key:0,class:"spouses-level"},re={class:"spouses-wrapper"},oe={class:"spouses-container"},ae={class:"relationship-label"},ie={key:1,class:"children-level"},de={class:"children-wrapper"},ue={class:"children-container"},me={class:"relationship-label"},ce={class:"child-with-spouse"},pe={key:0,class:"spouses-of-child"},fe={class:"relationship-label"},ge={key:0,class:"grandchildren-level"},he={class:"grandchildren-wrapper"},ve={class:"grandchildren-container"},be={class:"relationship-label"},ye={key:0,class:"spouses-of-grandchild"},ke={__name:"FamilyTree",props:{person:Object},setup(n){const g=n,m=w(()=>g.person.parents?g.person.parents.filter((a,o,t)=>o===t.findIndex(i=>i.id===a.id)):[]),p=w(()=>g.person.spouses?g.person.spouses.filter((a,o,t)=>o===t.findIndex(i=>i.id===a.id)):[]),s=w(()=>g.person.children?g.person.children.filter((a,o,t)=>o===t.findIndex(i=>i.id===a.id)):[]),d=a=>a.children?a.children.filter((o,t,i)=>t===i.findIndex(P=>P.id===o.id)):[],T=a=>a.spouses?a.spouses.filter(o=>{var t;return!((t=g.person.parents)!=null&&t.some(i=>i.id===o.id))}):[],I=a=>a.spouses?a.spouses.filter(o=>{var t;return!((t=g.person.children)!=null&&t.some(i=>i.id===o.id))}):[],S=(a,o)=>a.spouses?a.spouses.filter(t=>{var i;return!((i=o.children)!=null&&i.some(P=>P.id===t.id))}):[],L=a=>a.gender==="male"?"Ayah":"Ibu",C=(a,o)=>a.gender==="male"?o.gender==="male"?"Pasangan (Laki-laki)":"Istri":o.gender==="female"?"Pasangan (Perempuan)":"Suami",_=a=>g.person.gender==="male"?a.gender==="male"?"Pasangan":"Istri":a.gender==="female"?"Pasangan":"Suami",h=a=>a.gender==="male"?"Anak Laki-laki":"Anak Perempuan";return(a,o)=>(l(),r("div",U,[n.person?(l(),r("div",J,[n.person.parents&&n.person.parents.length>0?(l(),r("div",Y,[e("div",Q,[e("div",W,[(l(!0),r(b,null,y(m.value,t=>(l(),r("div",{key:t.id,class:"parent-node"},[e("div",X,u(L(t)),1),c(k,{person:t},null,8,["person"]),t.spouses&&t.spouses.length>0?(l(),r("div",Z,[(l(!0),r(b,null,y(T(t),i=>(l(),r("div",{key:`parent-spouse-${i.id}`,class:"spouse-of-parent-node"},[e("div",ee,u(C(t,i)),1),o[0]||(o[0]=e("div",{class:"connector-line horizontal"},null,-1)),c(k,{person:i},null,8,["person"])]))),128))])):f("",!0)]))),128))])]),o[1]||(o[1]=e("div",{class:"connector-line vertical"},null,-1))])):f("",!0),e("div",se,[e("div",ne,[e("div",te,u((n.person.gender==="male","Kepala Keluarga")),1),c(k,{person:n.person,isMain:!0},null,8,["person"])]),n.person.spouses&&n.person.spouses.length>0?(l(),r("div",le,[e("div",re,[e("div",oe,[(l(!0),r(b,null,y(p.value,t=>(l(),r("div",{key:t.id,class:"spouse-node"},[e("div",ae,u(_(t)),1),o[2]||(o[2]=e("div",{class:"connector-line horizontal"},null,-1)),c(k,{person:t},null,8,["person"])]))),128))])])])):f("",!0)]),n.person.children&&n.person.children.length>0?(l(),r("div",ie,[o[7]||(o[7]=e("div",{class:"connector-line vertical"},null,-1)),e("div",de,[e("div",ue,[(l(!0),r(b,null,y(s.value,t=>(l(),r("div",{key:t.id,class:"child-node"},[e("div",me,u(h(t)),1),e("div",ce,[c(k,{person:t},null,8,["person"]),t.spouses&&t.spouses.length>0?(l(),r("div",pe,[(l(!0),r(b,null,y(I(t),i=>(l(),r("div",{key:`child-spouse-${i.id}`,class:"spouse-of-child-node"},[e("div",fe," Menantu "+u(i.gender==="male"?"Laki-laki":"Perempuan"),1),o[3]||(o[3]=e("div",{class:"connector-line horizontal"},null,-1)),c(k,{person:i},null,8,["person"])]))),128))])):f("",!0)]),t.children&&t.children.length>0?(l(),r("div",ge,[o[6]||(o[6]=e("div",{class:"connector-line vertical"},null,-1)),e("div",he,[e("div",ve,[(l(!0),r(b,null,y(d(t),i=>(l(),r("div",{key:i.id,class:"grandchild-node"},[e("div",be," Cucu "+u(i.gender==="male"?"Laki-laki":"Perempuan"),1),c(k,{person:i},null,8,["person"]),i.spouses&&i.spouses.length>0?(l(),r("div",ye,[(l(!0),r(b,null,y(S(i,t),P=>(l(),r("div",{key:`grandchild-spouse-${P.id}`,class:"spouse-of-grandchild-node"},[o[4]||(o[4]=e("div",{class:"relationship-label"}," Pasangan Cucu ",-1)),o[5]||(o[5]=e("div",{class:"connector-line horizontal"},null,-1)),c(k,{person:P},null,8,["person"])]))),128))])):f("",!0)]))),128))])])])):f("",!0)]))),128))])])])):f("",!0)])):f("",!0)]))}},xe=D(ke,[["__scopeId","data-v-bd6cecfe"]]),$e={class:"font-semibold text-xl text-gray-800 leading-tight"},we={class:"py-6"},_e={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},Pe={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},Me={class:"p-6 bg-white border-b border-gray-200"},Te={class:"flex justify-between items-start mb-6"},Ie={class:"flex space-x-2"},Se={class:"grid grid-cols-1 md:grid-cols-2 gap-8"},Le={class:"mb-4"},Ce={class:"mt-2 space-y-2"},Ne={key:0},De={class:"mt-2 text-gray-700"},Be={class:"mb-6"},Ke={class:"mt-4 space-y-4"},je={key:0},Ae={class:"mt-2 space-y-1"},Fe={key:1},Oe={class:"mt-2 space-y-1"},ze={key:0,class:"text-sm text-gray-500 ml-2"},Re={key:2},qe={class:"mt-2 space-y-1"},Ve={key:3},He={class:"mt-8"},Ee={class:"mt-4 p-4 border rounded bg-gray-50 overflow-x-auto"},Ge={class:"min-w-max"},Ue={__name:"Show",props:{person:Object,familyTree:Object},setup(n){const g=(p,s)=>{const d=z(p,s);return d===null?"Tidak diketahui":`${d} tahun`+(s?" (meninggal)":"")},m=p=>p?R(new Date(p),"dd MMMM yyyy"):null;return(p,s)=>(l(),j(F,null,{header:x(()=>[e("h2",$e,u(n.person.name),1)]),default:x(()=>[c($(A),{title:"Detail "+n.person.name},null,8,["title"]),e("div",we,[e("div",_e,[e("div",Pe,[e("div",Me,[e("div",Te,[s[2]||(s[2]=e("div",null,[e("h3",{class:"text-lg font-medium"},"Informasi Pribadi"),e("p",{class:"text-sm text-gray-500"},"Detail anggota keluarga")],-1)),e("div",Ie,[c($(M),{href:p.route("people.edit",n.person.id),class:"inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"},{default:x(()=>s[0]||(s[0]=[v(" Edit ")])),_:1},8,["href"]),c($(M),{href:p.route("relationships.create",{person_id:n.person.id}),class:"inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"},{default:x(()=>s[1]||(s[1]=[v(" Tambah Relasi ")])),_:1},8,["href"])])]),e("div",Se,[e("div",null,[e("div",Le,[s[8]||(s[8]=e("h4",{class:"font-medium text-gray-900"},"Informasi Dasar",-1)),e("div",Ce,[e("p",null,[s[3]||(s[3]=e("span",{class:"font-medium"},"Jenis Kelamin : ",-1)),v(" "+u(n.person.gender==="male"?"Laki-laki":"Perempuan"),1)]),e("p",null,[s[4]||(s[4]=e("span",{class:"font-medium"},"Tanggal Lahir : ",-1)),v(" "+u(m(n.person.birth_date)||"Tidak diketahui"),1)]),e("p",null,[s[5]||(s[5]=e("span",{class:"font-medium"},"Usia :",-1)),v(" "+u(g(n.person.birth_date,n.person.death_date)),1)]),n.person.death_date?(l(),r("p",Ne,[s[6]||(s[6]=e("span",{class:"font-medium"},"Tanggal Meninggal : ",-1)),v(" "+u(m(n.person.death_date)),1)])):f("",!0),e("p",null,[s[7]||(s[7]=e("span",{class:"font-medium"},"Status :",-1)),e("span",{class:N(n.person.death_date?"text-red-600":"text-green-600")},u(n.person.death_date?" Meninggal":" Masih Hidup"),3)])])]),e("div",null,[s[9]||(s[9]=e("h4",{class:"font-medium text-gray-900"},"Biografi",-1)),e("p",De,u(n.person.bio||"Tidak ada biografi tersedia."),1)])]),e("div",null,[e("div",Be,[s[17]||(s[17]=e("h4",{class:"font-medium text-gray-900"},"Hubungan Keluarga",-1)),e("div",Ke,[n.person.parents.length>0?(l(),r("div",je,[s[11]||(s[11]=e("h5",{class:"font-medium"},"Orang Tua",-1)),e("ul",Ae,[(l(!0),r(b,null,y(n.person.parents,d=>(l(),r("li",{key:d.id,class:"flex items-center"},[c($(M),{href:p.route("people.show",d.id),class:"text-blue-600 hover:text-blue-800 flex items-center"},{default:x(()=>[s[10]||(s[10]=e("span",{class:"inline-block w-2 h-2 rounded-full bg-blue-600 mr-2"},null,-1)),v(" "+u(d.name),1)]),_:2},1032,["href"])]))),128))])])):f("",!0),n.person.spouses.length>0?(l(),r("div",Fe,[s[13]||(s[13]=e("h5",{class:"font-medium"},"Pasangan",-1)),e("ul",Oe,[(l(!0),r(b,null,y(n.person.spouses,d=>(l(),r("li",{key:d.id,class:"flex items-center"},[c($(M),{href:p.route("people.show",d.id),class:"text-blue-600 hover:text-blue-800 flex items-center"},{default:x(()=>[s[12]||(s[12]=e("span",{class:"inline-block w-2 h-2 rounded-full bg-blue-600 mr-2"},null,-1)),v(" "+u(d.name),1)]),_:2},1032,["href"]),d.pivot.marriage_id?(l(),r("span",ze," (Menikah) ")):f("",!0)]))),128))])])):f("",!0),n.person.children.length>0?(l(),r("div",Re,[s[15]||(s[15]=e("h5",{class:"font-medium"},"Anak-anak",-1)),e("ul",qe,[(l(!0),r(b,null,y(n.person.children,d=>(l(),r("li",{key:d.id,class:"flex items-center"},[c($(M),{href:p.route("people.show",d.id),class:"text-blue-600 hover:text-blue-800 flex items-center"},{default:x(()=>[s[14]||(s[14]=e("span",{class:"inline-block w-2 h-2 rounded-full bg-blue-600 mr-2"},null,-1)),v(" "+u(d.name),1)]),_:2},1032,["href"])]))),128))])])):f("",!0),n.person.parents.length===0&&n.person.spouses.length===0&&n.person.children.length===0?(l(),r("div",Ve,s[16]||(s[16]=[e("p",{class:"text-gray-500"},"Belum ada hubungan keluarga yang ditambahkan.",-1)]))):f("",!0)])])])]),e("div",He,[s[18]||(s[18]=e("h4",{class:"font-medium text-gray-900"},"Pohon Keluarga",-1)),e("div",Ee,[e("div",Ge,[c(xe,{person:n.person},null,8,["person"])])])])])])])])]),_:1}))}},Ze=D(Ue,[["__scopeId","data-v-50dd2c73"]]);export{Ze as default};

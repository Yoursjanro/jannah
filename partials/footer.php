<div class="page-progress" aria-hidden="true"></div>
<div class="cursor-glow" aria-hidden="true"></div>
<div class="toast" role="status" aria-live="polite"></div>
<footer class="footer reveal"><p>Data Structures Activity Lab • Built with XAMPP, PHP, MySQL, HTML/CSS and JavaScript.</p></footer>
<script>
(function(){
  if(window.__dsInteractive) return; window.__dsInteractive=true;
  const reduce=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const progress=document.querySelector('.page-progress'),toast=document.querySelector('.toast'),glow=document.querySelector('.cursor-glow');
  function showToast(msg){if(!toast)return;toast.textContent=msg;toast.classList.add('show');clearTimeout(window.__dsToast);window.__dsToast=setTimeout(()=>toast.classList.remove('show'),1800)}
  document.querySelectorAll('.nav-toggle').forEach(b=>b.addEventListener('click',()=>{const n=document.querySelector('.nav-links');n&&n.classList.toggle('open')}));
  const targets=document.querySelectorAll('.hero-card,.info-card,.type-card,.activity-card,.panel,.catalog-group,.runner-layout,.searchbar,.page-heading,.auth-card,.doc-grid,.section-label');
  const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('is-visible');io.unobserve(e.target)}}),{threshold:.1});
  targets.forEach((e,i)=>{e.classList.add('reveal');if(i%4===1)e.classList.add('delay-1');if(i%4===2)e.classList.add('delay-2');if(i%4===3)e.classList.add('delay-3');io.observe(e)});
  function scroll(){if(progress){const h=document.documentElement.scrollHeight-innerHeight;progress.style.width=(h>0?(scrollY/h)*100:0)+'%'}} addEventListener('scroll',scroll,{passive:true});scroll();
  document.querySelectorAll('.btn,.activity-card a,.type-card,.nav-links a').forEach(el=>el.addEventListener('pointerdown',function(e){const r=this.getBoundingClientRect(),s=Math.max(r.width,r.height)*1.4,x=e.clientX-r.left-s/2,y=e.clientY-r.top-s/2,q=document.createElement('span');q.className='ripple';q.style.width=q.style.height=s+'px';q.style.left=x+'px';q.style.top=y+'px';this.appendChild(q);setTimeout(()=>q.remove(),650)}));
  document.querySelectorAll('input,textarea,select').forEach(el=>{el.addEventListener('input',()=>el.classList.toggle('has-value',!!el.value));el.addEventListener('focus',()=>showToast('Ready — type or edit your input'));});
  if(!reduce){
    document.body.insertBefore(document.createComment('interactive glow'),document.body.firstChild);
    addEventListener('pointermove',e=>{if(!glow)return;glow.style.left=e.clientX+'px';glow.style.top=e.clientY+'px';document.body.classList.add('has-cursor-glow')},{passive:true});
    document.querySelectorAll('.activity-card,.info-card,.type-card').forEach(card=>{card.addEventListener('pointermove',e=>{if(e.pointerType!=='mouse')return;const r=card.getBoundingClientRect(),x=(e.clientX-r.left)/r.width-.5,y=(e.clientY-r.top)/r.height-.5;card.style.transform=`perspective(800px) rotateX(${(-y*3).toFixed(2)}deg) rotateY(${(x*4).toFixed(2)}deg) translateY(-7px)`});card.addEventListener('pointerleave',()=>card.style.transform='')});
    document.querySelectorAll('.hero-card').forEach(card=>card.addEventListener('pointermove',e=>{const r=card.getBoundingClientRect(),x=(e.clientX-r.left)/r.width-.5,y=(e.clientY-r.top)/r.height-.5;card.style.backgroundPosition=`${50+x*8}% ${50+y*8}%`}));
  }
  document.querySelectorAll('.btn,.activity-card,.type-card').forEach(el=>{el.addEventListener('pointerdown',()=>el.classList.add('touching'));['pointerup','pointercancel','pointerleave'].forEach(ev=>el.addEventListener(ev,()=>el.classList.remove('touching')))});
  const title=document.querySelector('.hero-card h1');
  if(title&&!reduce&&title.dataset.typed!=='1'){const full=title.textContent.trim();title.dataset.typed='1';title.textContent='';const caret=document.createElement('span');caret.className='typing-caret';let i=0;const tick=()=>{if(i<full.length){title.insertBefore(document.createTextNode(full[i++]),caret);setTimeout(tick,32)}else setTimeout(()=>caret.remove(),1800)};title.appendChild(caret);setTimeout(tick,250)}
  document.querySelectorAll('.nodes').forEach(n=>n.addEventListener('wheel',e=>{if(Math.abs(e.deltaY)>Math.abs(e.deltaX)){n.scrollLeft+=e.deltaY;e.preventDefault()}},{passive:false}));
})();
</script>

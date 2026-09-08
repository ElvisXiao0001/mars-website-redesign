'use client';

import { useEffect, useRef, useState } from 'react';
import type { MouseEvent } from 'react';
import { ArrowLeft, ArrowRight, Check, Menu, Moon, Plus, Sun, X } from 'lucide-react';

type View = 'home' | 'project' | 'progress';
const stages = ['兴趣调查', '打样', '预售', '团购', '生产中', '发货中', '已完成'];
const projects = [
  { name: 'RETRO RED LIGHT', maker: 'SWG', year: '2026', image: '/assets/retro-hero.jpg', ready: true },
  { name: 'PURE PLAYER: XY', maker: 'KEYREATIVE', year: '2026', image: '/assets/render-1.jpg' },
  { name: 'FAN U.C', maker: 'SWG', year: '2026', image: '/assets/render-2.jpg' },
  { name: 'RETRO>_R.C 759', maker: 'KEYKOBO', year: '2025', image: '/assets/render-5.jpg' },
  { name: 'OHM:R.E.D', maker: 'KEYKOBO', year: '2025', image: '/assets/kit-dark.jpg' },
];

function RollingText({ text }: { text: string }) {
  return <span className="rolling-text" aria-hidden="true">{Array.from(text).map((char, index) => <span className="rolling-char" key={`${char}-${index}`}><span className="rolling-track" style={{ transitionDelay: `${index * 12}ms` }}><span>{char === ' ' ? '\u00a0' : char}</span><span>{char === ' ' ? '\u00a0' : char}</span></span></span>)}</span>;
}

export default function Home() {
  const [view, setView] = useState<View>('home');
  const [menuOpen, setMenuOpen] = useState(false);
  const [menuClosing, setMenuClosing] = useState(false);
  const [dark, setDark] = useState(false);
  const [lang, setLang] = useState<'zh' | 'en'>('zh');
  const [hovered, setHovered] = useState<number | null>(null);
  const [previewVisible, setPreviewVisible] = useState(false);
  const [previousImage, setPreviousImage] = useState<string | null>(null);
  const previewRef = useRef<HTMLDivElement | null>(null);
  const previewHideTimer = useRef<number | null>(null);
  const previewSwapTimer = useRef<number | null>(null);
  const targetPointer = useRef({ x: 0, y: 0 });
  const smoothPointer = useRef({ x: 0, y: 0 });
  const pointerReady = useRef(false);
  const [lightbox, setLightbox] = useState<string | null>(null);

  useEffect(() => {
    document.documentElement.classList.toggle('dark', dark);
  }, [dark]);
  useEffect(() => {
    if (hovered === null) return;
    let frame = 0;
    const animate = () => {
      smoothPointer.current.x += (targetPointer.current.x - smoothPointer.current.x) * 0.115;
      smoothPointer.current.y += (targetPointer.current.y - smoothPointer.current.y) * 0.115;
      const preview = previewRef.current;
      if (preview) {
        preview.style.left = `${smoothPointer.current.x}px`;
        preview.style.top = `${smoothPointer.current.y}px`;
        const drift = Math.max(-58, Math.min(58, (targetPointer.current.x - smoothPointer.current.x) * -0.16));
        preview.querySelectorAll<HTMLImageElement>('img').forEach(image => {
          image.style.transform = `scale(1.045) translate3d(${drift}px, 0, 0)`;
        });
      }
      frame = window.requestAnimationFrame(animate);
    };
    frame = window.requestAnimationFrame(animate);
    return () => window.cancelAnimationFrame(frame);
  }, [hovered]);
  const go = (next: View) => { setView(next); setMenuOpen(false); setMenuClosing(false); window.scrollTo({ top: 0, behavior: 'smooth' }); };
  const closeMenu = () => {
    setMenuClosing(true);
    window.setTimeout(() => { setMenuOpen(false); setMenuClosing(false); }, 360);
  };
  const movePreview = (event: MouseEvent<HTMLElement>) => {
    targetPointer.current = { x: event.clientX, y: event.clientY };
    if (hovered !== null) {
      const row = document.querySelectorAll<HTMLElement>('.home-screen .project-row')[hovered];
      const title = row?.querySelector<HTMLElement>('.row-title');
      if (title) {
        const offset = ((event.clientX / window.innerWidth) - .5) * 90;
        title.style.transform = `translate3d(${offset}px,0,0)`;
      }
    }
  };
  const enterProject = (index: number, event: MouseEvent<HTMLButtonElement>) => {
    if (previewHideTimer.current !== null) window.clearTimeout(previewHideTimer.current);
    if (hovered !== null && hovered !== index) {
      if (previewSwapTimer.current !== null) window.clearTimeout(previewSwapTimer.current);
      setPreviousImage(projects[hovered].image);
      previewSwapTimer.current = window.setTimeout(() => setPreviousImage(null), 820);
    }
    document.querySelectorAll<HTMLElement>('.row-title').forEach((title, titleIndex) => {
      if (titleIndex !== index) title.style.transform = '';
    });
    targetPointer.current = { x: event.clientX, y: event.clientY };
    if (!pointerReady.current) {
      smoothPointer.current = { x: event.clientX, y: event.clientY };
      pointerReady.current = true;
    }
    setHovered(index);
    window.requestAnimationFrame(() => window.requestAnimationFrame(() => setPreviewVisible(true)));
  };

  return <main>
    <header className="site-header">
      <button className="brand" onClick={() => go('home')} aria-label="Mars Universe 首页"><img src="/assets/mars-logo-trimmed.png" alt="Mars Universe" /></button>
      <div className="header-actions">
        <button className="text-button" onClick={() => setLang(lang === 'zh' ? 'en' : 'zh')}>{lang === 'zh' ? 'EN' : '中文'}</button>
        <button className="icon-button" onClick={() => setDark(!dark)} aria-label="切换黑白模式">{dark ? <Sun size={18} /> : <Moon size={18} />}</button>
        <button className="menu-button" onClick={() => { setMenuClosing(false); setMenuOpen(true); }}><span>MENU</span><Menu size={20} strokeWidth={1.8} /></button>
      </div>
    </header>

    {view === 'home' && <section className="home-screen" onMouseMove={movePreview}>
      <div className="project-list" onMouseLeave={() => { document.querySelectorAll<HTMLElement>('.row-title').forEach(title => { title.style.transform = ''; }); setPreviewVisible(false); pointerReady.current = false; previewHideTimer.current = window.setTimeout(() => setHovered(null), 700); }}>{projects.map((project, index) => <button key={project.name} className="project-row" onMouseEnter={(event) => enterProject(index, event)} onFocus={() => { setHovered(index); setPreviewVisible(true); }} onBlur={() => { setPreviewVisible(false); previewHideTimer.current = window.setTimeout(() => setHovered(null), 700); }} onClick={() => project.ready && go('project')}><span className="row-maker">{project.maker}</span><span className="row-title">{project.name}</span><span className="row-year">{project.year}</span></button>)}</div>
      {hovered !== null && <div className={`hover-preview ${previewVisible ? 'preview-visible' : ''}`} ref={previewRef}>{previousImage && <img className="preview-previous" src={previousImage} alt="" />}<img className={`preview-current ${previousImage ? 'is-switching' : ''}`} key={projects[hovered].image} src={projects[hovered].image} alt="" /></div>}
    </section>}

    {view === 'project' && <article className="project-page">
      <section className="project-hero"><img src="/assets/retro-hero.jpg" alt="Retro Red Light 键帽渲染" /><div className="hero-copy"><button className="back-link" onClick={() => go('home')}><ArrowLeft size={16} /> 返回项目</button><p className="eyebrow">DOUBLE-SHOT · RETRO VIBE · SWG</p><h1>RETRO<br />RED LIGHT</h1><span className="status-pill">当前阶段 · 打样</span></div></section>
      <section className="content-grid"><p className="section-number">01 / INSPIRATION</p><div><h2>灵感来自一台<br />1982 年的终端键盘。</h2><p>Retro Red Light 的设计灵感来自 Data General 6246。克制的灰阶、红色功能键与工业标签，共同构成这套键帽的视觉语言。</p></div></section>
      <section className="specs">{[['制造商','SWG'],['材质','ABS'],['工艺','DOUBLE-SHOT'],['预计发布','2026 · Q4']].map(([label,value]) => <div key={label}><span>{label}</span><strong>{value}</strong></div>)}</section>
      <ProgressStrip />
      <Gallery number="02" title="套件与布局" note="点击图片查看产品细节" images={['/assets/kit-general.jpg','/assets/kit-dark.jpg','/assets/kit-extensions.jpg']} onOpen={setLightbox} />
      <Gallery number="03" title="产品渲染" note="更多图片可从后台添加" images={['/assets/render-1.jpg','/assets/render-2.jpg','/assets/render-5.jpg']} onOpen={setLightbox} />
      <section className="next-project"><span>NEXT PROJECT</span><h2>PURE PLAYER: XY</h2><ArrowRight size={36} /></section>
    </article>}

    {view === 'progress' && <section className="progress-page">
      <div className="page-title"><p className="eyebrow">PROJECTS / 2026</p><h1>项目进度</h1><p>查看所有项目当前所处的阶段与最近更新。</p></div>
      <div>{[
        ['Retro Red Light','SWG',1,'第二轮颜色样品制作中','2026.09.02'],
        ['FAN U.C','SWG',3,'团购阶段进行中','2026.08.28'],
        ['Retro>_R.C 759','KEYKOBO',4,'生产排期已确认','2026.08.16'],
        ['OHM:R.E.D','KEYKOBO',6,'项目已完成','2026.07.30'],
      ].map(([name,maker,stage,note,date]) => <div className="progress-item" key={name as string}>
        <div className="progress-head"><div><span>{maker}</span><h2>{name}</h2></div><button onClick={() => name === 'Retro Red Light' && go('project')}>查看项目 <ArrowRight size={17} /></button></div>
        <div className="mini-track">{stages.map((label,i) => <div className={i < Number(stage) ? 'done' : i === Number(stage) ? 'current' : ''} key={label}><i>{i < Number(stage) ? <Check size={13} /> : i + 1}</i><span>{label}</span></div>)}</div>
        <div className="progress-note"><span>{date as string}</span><p>{note as string}</p></div>
      </div>)}</div>
    </section>}

    {menuOpen && <div className={`menu-overlay ${lang === 'zh' ? 'menu-zh' : 'menu-en'} ${menuClosing ? 'menu-closing' : ''}`} role="dialog" aria-modal="true" aria-label="主菜单">
      <div className="menu-top"><button className="menu-logo" onClick={() => go('home')} aria-label="Mars Universe"><img src="/assets/mars-logo-trimmed.png" alt="Mars Universe" /></button><div className="menu-actions"><button className="text-button" onClick={() => setLang(lang === 'zh' ? 'en' : 'zh')}>{lang === 'zh' ? 'EN' : '中文'}</button><button className="icon-button" onClick={() => setDark(!dark)} aria-label="切换黑白模式">{dark ? <Sun size={18} /> : <Moon size={18} />}</button><button className="menu-close" onClick={closeMenu}><X size={20} strokeWidth={1.8} /> {lang === 'zh' ? '关闭' : 'CLOSE'}</button></div></div>
      <nav>
        <button aria-label={lang === 'zh' ? '首页' : 'HOME'} onClick={() => go('home')}><RollingText text={lang === 'zh' ? '首页' : 'HOME'} /></button>
        <button aria-label={lang === 'zh' ? '全部项目' : 'ALL PROJECTS'} onClick={() => go('home')}><RollingText text={lang === 'zh' ? '全部项目' : 'ALL PROJECTS'} /></button>
        <button aria-label={lang === 'zh' ? '项目进度' : 'PROJECT PROGRESS'} onClick={() => go('progress')}><RollingText text={lang === 'zh' ? '项目进度' : 'PROJECT PROGRESS'} /></button>
        {['SWG','KEYKOBO','KEYREATIVE','PBTFANS','GOMASTER'].map(maker => <button className="maker-link" aria-label={maker} key={maker}><span className="menu-label-wrap"><RollingText text={maker} /><Plus size={18} /></span></button>)}
      </nav>
      <footer className="menu-footer"><div><span>{lang === 'zh' ? '所在地' : 'LOCATION'}</span><p>{lang === 'zh' ? '中国，上海' : 'Shanghai, China'}</p></div><div><span>{lang === 'zh' ? '联系方式' : 'CONTACT'}</span><a href="mailto:admin@mars-universe.net">admin@mars-universe.net</a></div><div><span>{lang === 'zh' ? '社交媒体' : 'SOCIAL MEDIA'}</span><p>Instagram / Discord / QQ Group</p></div></footer>
    </div>}
    {lightbox && <div className="lightbox" role="dialog" aria-modal="true" onClick={() => setLightbox(null)}><button aria-label="关闭大图"><X /></button><img src={lightbox} alt="产品细节大图" /></div>}
  </main>;
}

function ProgressStrip() {
  return <section className="progress-strip"><div className="strip-heading"><p className="section-number">PROJECT PROGRESS</p><strong>当前阶段：打样</strong></div><div className="stage-track">{stages.map((stage,i) => <div className={i === 0 ? 'done' : i === 1 ? 'current' : ''} key={stage}><i>{i === 0 ? <Check size={15} /> : i + 1}</i><span>{stage}</span></div>)}</div><div className="stage-detail"><span>2026.09.02</span><p>第一次样品颜色偏深，目前正在调整并准备第二轮打样。</p></div></section>;
}

function Gallery({ number, title, note, images, onOpen }: { number:string; title:string; note:string; images:string[]; onOpen:(image:string)=>void }) {
  return <section className="gallery-section"><div className="gallery-heading"><span>{number}</span><h2>{title}</h2><p>{note}</p></div><div className="gallery-grid">{images.map((image,i) => <button key={image} className={i === 0 ? 'wide' : ''} onClick={() => onOpen(image)}><img src={image} alt={`${title} ${i + 1}`} loading="lazy" /><span><Plus size={20} /> 查看细节</span></button>)}</div></section>;
}

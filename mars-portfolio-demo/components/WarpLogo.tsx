'use client';

import { useEffect, useRef, useState } from 'react';
import { Mesh, Program, Renderer, Texture, Triangle } from 'ogl';

const vertex = `#version 300 es
in vec2 position;
in vec2 uv;
out vec2 vUv;
void main(){vUv=uv;gl_Position=vec4(position,0.0,1.0);}`;

const fragment = `#version 300 es
precision highp float;
uniform sampler2D uTexture;
uniform vec2 uPointer;
uniform float uPointerActive;
uniform float uTime;
in vec2 vUv;
out vec4 fragColor;
float hash(vec2 p){p=fract(p*vec2(123.34,456.21));p+=dot(p,p+45.32);return fract(p.x*p.y);}
float noise(vec2 p){vec2 i=floor(p),f=fract(p);vec2 u=f*f*(3.0-2.0*f);return mix(mix(hash(i),hash(i+vec2(1,0)),u.x),mix(hash(i+vec2(0,1)),hash(i+vec2(1)),u.x),u.y);}
void main(){
  vec2 uv=vUv;
  float t=uTime*.2;
  vec2 ambient=(vec2(noise(uv*5.2+vec2(t,-t)),noise(uv*5.6+vec2(8.7-t,t)))-.5)*.009;
  vec2 delta=uv-uPointer;
  float dist=length(vec2(delta.x*5.0,delta.y));
  float lens=smoothstep(.44,0.0,dist)*uPointerActive;
  vec2 dir=dist>.0001?normalize(delta):vec2(0.0);
  // Keep every pixel outside the pointer field at its original UV.
  // Both the flowing texture and refraction now decay with the local lens.
  vec2 warp=(ambient*1.4-dir*(1.0-lens)*.1)*lens;
  vec2 displaced=uv+warp;
  vec2 split=normalize(warp+vec2(.0001))*.0022*lens;
  vec4 base=texture(uTexture,displaced);
  vec4 left=texture(uTexture,displaced-split);
  vec4 right=texture(uTexture,displaced+split);
  fragColor=vec4(right.r,base.g,left.b,max(base.a,max(left.a,right.a)));
}`;

export default function WarpLogo({ src, alt }: { src: string; alt: string }) {
  const rootRef = useRef<HTMLSpanElement | null>(null);
  const [ready, setReady] = useState(false);

  useEffect(() => {
    const root = rootRef.current;
    if (!root || !window.matchMedia('(min-width: 781px)').matches || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    let renderer: Renderer | undefined;
    let frame = 0;
    let disposed = false;
    let observer: ResizeObserver | undefined;
    const pointer = { x: .5, y: .5, tx: .5, ty: .5, active: 0, target: 0 };

    try {
      renderer = new Renderer({ webgl: 2, alpha: true, premultipliedAlpha: false, antialias: true, dpr: Math.min(devicePixelRatio, 2) });
    } catch {
      return;
    }
    const gl = renderer.gl;
    gl.clearColor(0, 0, 0, 0);
    const canvas = gl.canvas as HTMLCanvasElement;
    canvas.className = 'warp-logo-canvas';
    root.appendChild(canvas);
    const texture = new Texture(gl, { generateMipmaps: false, minFilter: gl.LINEAR, magFilter: gl.LINEAR });
    const program = new Program(gl, { vertex, fragment, transparent: true, depthTest: false, depthWrite: false, uniforms: {
      uTexture: { value: texture }, uPointer: { value: new Float32Array([.5, .5]) }, uPointerActive: { value: 0 }, uTime: { value: 0 }
    }});
    const geometry = new Triangle(gl);
    const mesh = new Mesh(gl, { geometry, program });
    const image = new Image();
    image.src = src;
    image.decode().then(() => {
      if (disposed) return;
      const source = document.createElement('canvas');
      source.width = 2048;
      source.height = Math.round(2048 * 422.91 / 5234.59);
      const context = source.getContext('2d');
      if (!context) return;
      context.clearRect(0, 0, source.width, source.height);
      context.drawImage(image, 0, 0, source.width, source.height);
      texture.image = source;
      texture.needsUpdate = true;
      renderer?.render({ scene: mesh });
      requestAnimationFrame(() => { if (!disposed) setReady(true); });
    }).catch(() => undefined);

    const resize = () => { const rect = root.getBoundingClientRect(); if (rect.width && rect.height) renderer?.setSize(rect.width, rect.height); };
    const move = (event: globalThis.PointerEvent) => { const rect=root.getBoundingClientRect(); pointer.tx=(event.clientX-rect.left)/rect.width; pointer.ty=1-(event.clientY-rect.top)/rect.height; pointer.target=1; };
    const leave = () => { pointer.target=0; };
    const start = performance.now();
    const loop = (now: number) => {
      if (disposed || !renderer) return;
      pointer.x+=(pointer.tx-pointer.x)*.12; pointer.y+=(pointer.ty-pointer.y)*.12; pointer.active+=(pointer.target-pointer.active)*.09;
      program.uniforms.uPointer.value[0]=pointer.x; program.uniforms.uPointer.value[1]=pointer.y; program.uniforms.uPointerActive.value=pointer.active; program.uniforms.uTime.value=(now-start)*.001;
      renderer.render({ scene: mesh });
      if (pointer.target === 1 && pointer.active > .12) root.classList.add('is-interacting');
      if (pointer.target === 0 && pointer.active < .012) root.classList.remove('is-interacting');
      frame=requestAnimationFrame(loop);
    };
    observer = new ResizeObserver(resize); observer.observe(root); resize();
    root.addEventListener('pointermove', move); root.addEventListener('pointerleave', leave); frame=requestAnimationFrame(loop);
    return () => { disposed=true; cancelAnimationFrame(frame); observer?.disconnect(); root.removeEventListener('pointermove', move); root.removeEventListener('pointerleave', leave); canvas.remove(); };
  }, [src]);

  return <span ref={rootRef} className={`warp-logo ${ready ? 'is-ready' : ''}`}>
    <img className="warp-logo-desktop" src={src} alt={alt} />
    <img className="warp-logo-mobile" src="/assets/mars-logo-trimmed.png" alt="" />
  </span>;
}

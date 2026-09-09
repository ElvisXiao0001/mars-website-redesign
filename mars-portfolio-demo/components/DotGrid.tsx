'use client';

import { useEffect, useRef } from 'react';

type Dot = { x: number; y: number; ox: number; oy: number; vx: number; vy: number };

export default function DotGrid({ dark = false }: { dark?: boolean }) {
  const canvasRef = useRef<HTMLCanvasElement | null>(null);

  useEffect(() => {
    const canvas = canvasRef.current;
    const host = canvas?.parentElement;
    if (!canvas || !host || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    const context = canvas.getContext('2d');
    if (!context) return;
    let dots: Dot[] = [];
    let frame = 0;
    let lastX = -1000;
    let lastY = -1000;
    let lastTime = performance.now();
    let pointerX = -1000;
    let pointerY = -1000;
    const gap = 30;

    const resize = () => {
      const rect = host.getBoundingClientRect();
      const dpr = Math.min(window.devicePixelRatio || 1, 2);
      canvas.width = Math.round(rect.width * dpr);
      canvas.height = Math.round(rect.height * dpr);
      canvas.style.width = `${rect.width}px`;
      canvas.style.height = `${rect.height}px`;
      context.setTransform(dpr, 0, 0, dpr, 0, 0);
      dots = [];
      for (let y = gap / 2; y < rect.height; y += gap) {
        for (let x = gap / 2; x < rect.width; x += gap) dots.push({ x, y, ox: 0, oy: 0, vx: 0, vy: 0 });
      }
    };

    const move = (event: PointerEvent) => {
      const rect = host.getBoundingClientRect();
      const now = performance.now();
      const dt = Math.max(12, now - lastTime);
      pointerX = event.clientX - rect.left;
      pointerY = event.clientY - rect.top;
      const vx = Math.max(-38, Math.min(38, (event.clientX - lastX) / dt * 7));
      const vy = Math.max(-38, Math.min(38, (event.clientY - lastY) / dt * 7));
      for (const dot of dots) {
        const dx = dot.x - pointerX;
        const dy = dot.y - pointerY;
        const distance = Math.hypot(dx, dy);
        if (distance < 135) {
          const force = (1 - distance / 135) * .75;
          dot.vx += vx * force;
          dot.vy += vy * force;
        }
      }
      lastX = event.clientX;
      lastY = event.clientY;
      lastTime = now;
    };

    const shock = (event: PointerEvent) => {
      const rect = host.getBoundingClientRect();
      const x = event.clientX - rect.left;
      const y = event.clientY - rect.top;
      for (const dot of dots) {
        const dx = dot.x - x;
        const dy = dot.y - y;
        const distance = Math.max(1, Math.hypot(dx, dy));
        if (distance < 180) {
          const force = (1 - distance / 180) * 8;
          dot.vx += dx / distance * force;
          dot.vy += dy / distance * force;
        }
      }
    };

    const draw = () => {
      const rect = host.getBoundingClientRect();
      context.clearRect(0, 0, rect.width, rect.height);
      for (const dot of dots) {
        dot.vx += -dot.ox * .055;
        dot.vy += -dot.oy * .055;
        dot.vx *= .88;
        dot.vy *= .88;
        dot.ox += dot.vx;
        dot.oy += dot.vy;
        const proximity = Math.max(0, 1 - Math.hypot(dot.x - pointerX, dot.y - pointerY) / 145);
        const base = dark ? 68 : 205;
        const lift = dark ? 68 : -55;
        const value = Math.round(base + lift * proximity);
        context.beginPath();
        context.arc(dot.x + dot.ox, dot.y + dot.oy, 1.45 + proximity * .65, 0, Math.PI * 2);
        context.fillStyle = `rgb(${value},${value},${value})`;
        context.fill();
      }
      frame = requestAnimationFrame(draw);
    };

    const observer = new ResizeObserver(resize);
    observer.observe(host);
    resize();
    window.addEventListener('pointermove', move, { passive: true });
    window.addEventListener('pointerdown', shock, { passive: true });
    frame = requestAnimationFrame(draw);
    return () => {
      observer.disconnect();
      window.removeEventListener('pointermove', move);
      window.removeEventListener('pointerdown', shock);
      cancelAnimationFrame(frame);
    };
  }, [dark]);

  return <div className="home-dot-grid" aria-hidden="true"><canvas ref={canvasRef} /></div>;
}

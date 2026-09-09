'use client';

import { useRef, type MouseEvent, type ReactNode } from 'react';

type SpotlightCardProps = {
  children: ReactNode;
  className?: string;
  spotlightColor?: string;
};

export default function SpotlightCard({
  children,
  className = '',
  spotlightColor = 'rgba(255, 255, 255, 0.2)',
}: SpotlightCardProps) {
  const stageRef = useRef<HTMLDivElement | null>(null);
  const cardRef = useRef<HTMLDivElement | null>(null);
  const boundsRef = useRef<DOMRect | null>(null);

  const handleMouseEnter = () => {
    boundsRef.current = stageRef.current?.getBoundingClientRect() ?? null;
  };

  const handleMouseMove = (event: MouseEvent<HTMLDivElement>) => {
    const card = cardRef.current;
    if (!card) return;
    const rect = boundsRef.current ?? stageRef.current?.getBoundingClientRect();
    if (!rect) return;
    card.style.setProperty('--mouse-x', `${event.clientX - rect.left}px`);
    card.style.setProperty('--mouse-y', `${event.clientY - rect.top}px`);
    card.style.setProperty('--spotlight-color', spotlightColor);
    const normalizedX = Math.max(-1, Math.min(1, (event.clientX - rect.left) / rect.width * 2 - 1));
    const normalizedY = Math.max(-1, Math.min(1, (event.clientY - rect.top) / rect.height * 2 - 1));
    card.style.setProperty('--card-shift-x', `${normalizedX * 10}px`);
    card.style.setProperty('--card-shift-y', `${normalizedY * 7}px`);
    card.style.setProperty('--card-rotate-x', `${normalizedY * -1.2}deg`);
    card.style.setProperty('--card-rotate-y', `${normalizedX * 1.2}deg`);
  };

  const handleMouseLeave = () => {
    const card = cardRef.current;
    boundsRef.current = null;
    if (!card) return;
    card.style.setProperty('--card-shift-x', '0px');
    card.style.setProperty('--card-shift-y', '0px');
    card.style.setProperty('--card-rotate-x', '0deg');
    card.style.setProperty('--card-rotate-y', '0deg');
  };

  return <div ref={stageRef} onMouseEnter={handleMouseEnter} onMouseMove={handleMouseMove} onMouseLeave={handleMouseLeave} className="spotlight-card-stage"><div ref={cardRef} className={`card-spotlight ${className}`}>{children}</div></div>;
}

import { useEffect, useRef } from 'react';

interface HellFireProps {
  className?: string;
  intensity?: number;
  speed?: number;
}

/**
 * A full-screen animated "hell fire" texture rendered on canvas.
 * Uses layered flowing noise + ember particles to create a molten/hellish background.
 */
export default function HellFire({
  className = '',
  intensity = 1,
  speed = 1
}: HellFireProps) {
  const canvasRef = useRef<HTMLCanvasElement>(null);

  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    let width = (canvas.width = canvas.offsetWidth);
    let height = (canvas.height = canvas.offsetHeight);
    let raf = 0;
    let t = 0;

    // Ember particles
    const embers: { x: number; y: number; r: number; vy: number; vx: number; tw: number }[] = [];
    const EMBER_COUNT = 90;
    for (let i = 0; i < EMBER_COUNT; i++) {
      embers.push({
        x: Math.random() * width,
        y: height + Math.random() * height,
        r: 0.8 + Math.random() * 2.6,
        vy: 0.4 + Math.random() * 1.6,
        vx: (Math.random() - 0.5) * 0.6,
        tw: Math.random() * Math.PI * 2
      });
    }

    const resize = () => {
      width = canvas.width = canvas.offsetWidth;
      height = canvas.height = canvas.offsetHeight;
    };
    window.addEventListener('resize', resize);

    // Simple value-noise function for the flame turbulence
    const rand = (x: number, y: number) => {
      const s = Math.sin(x * 12.9898 + y * 78.233) * 43758.5453;
      return s - Math.floor(s);
    };
    const smooth = (v: number) => v * v * (3 - 2 * v);
    const noise = (x: number, y: number) => {
      const xi = Math.floor(x);
      const yi = Math.floor(y);
      const xf = x - xi;
      const yf = y - yi;
      const a = rand(xi, yi);
      const b = rand(xi + 1, yi);
      const c = rand(xi, yi + 1);
      const d = rand(xi + 1, yi + 1);
      const u = smooth(xf);
      const v = smooth(yf);
      return a + (b - a) * u + (c - a) * v + (a - b - c + d) * u * v;
    };
    const fbm = (x: number, y: number) => {
      let value = 0;
      let amp = 0.5;
      let freq = 1;
      for (let oct = 0; oct < 5; oct++) {
        value += amp * noise(x * freq, y * freq);
        amp *= 0.5;
        freq *= 2;
      }
      return value;
    };

    const render = () => {
      raf = requestAnimationFrame(render);
      t += 0.012 * speed;

      // Base dark backdrop
      ctx.fillStyle = '#0a0500';
      ctx.fillRect(0, 0, width, height);

// Fire color stops (hell palette) - dimmed for a subtler look
      const stops = [
        [0, '#2a0600'],
        [0.25, '#571200'],
        [0.5, '#8f2a00'],
        [0.7, '#c65a00'],
        [1, '#e89a3a']
      ];

      // Render layered fire using screen blending
      const cell = 56;
      for (let layer = 0; layer < 3; layer++) {
        const scale = 0.6 + layer * 0.35;
        const baseY = height * (0.35 + layer * 0.22);
        const img = ctx.createImageData(width, height);
        const data = img.data;
        for (let y = 0; y < height; y += 2) {
          for (let x = 0; x < width; x += 2) {
            const nx = x / width;
            const ny = y / height;
            const flame = fbm(
              nx * 3.2 * scale + t * 0.25,
              ny * 2.4 * scale - t * 0.18 * scale
            );
            // Shape flame toward bottom (rising heat)
            const rise = Math.pow(ny, 1.6);
            const heat = clamp(flame * 0.7 + rise * 0.4 + (1 - Math.abs(nx - 0.5) * 1.4) * 0.1, 0, 1);
            const heatBoost = clamp(heat * intensity, 0, 1);

            // Map heat to color gradient
            let r = 0,
              g = 0,
              b = 0;
            for (let s = 0; s < stops.length - 1; s++) {
              const [p0, c0] = stops[s] as [number, string];
              const [p1, c1] = stops[s + 1] as [number, string];
              if (heatBoost >= p0 && heatBoost <= p1) {
                const f = (heatBoost - p0) / (p1 - p0);
                const rgb0 = hex(c0);
                const rgb1 = hex(c1);
                r = lerp(rgb0[0], rgb1[0], f);
                g = lerp(rgb0[1], rgb1[1], f);
                b = lerp(rgb0[2], rgb1[2], f);
                break;
              }
            }

            // Screen blend over existing base
            const idx = (y * width + x) * 4;
            const exR = data[idx];
            const exG = data[idx + 1];
            const exB = data[idx + 2];
            data[idx] = 255 - ((255 - r) * (255 - exR)) / 255;
            data[idx + 1] = 255 - ((255 - g) * (255 - exG)) / 255;
            data[idx + 2] = 255 - ((255 - b) * (255 - exB)) / 255;
            data[idx + 3] = 255;
          }
        }
        ctx.putImageData(img, 0, 0);
      }

// Embers / sparks - subtler
      for (const e of embers) {
        e.y -= e.vy * (0.3 + intensity * 0.4);
        e.x += e.vx + Math.sin(t * 2 + e.tw) * 0.4;
        if (e.y < -10) {
          e.y = height + 10;
          e.x = Math.random() * width;
        }
        const flicker = 0.5 + 0.5 * Math.sin(t * 6 + e.tw);
        const alpha = (0.35 + 0.35 * flicker) * 0.7;
        const grad = ctx.createRadialGradient(e.x, e.y, 0, e.x, e.y, e.r * 4);
        grad.addColorStop(0, `rgba(255,200,110,${alpha})`);
        grad.addColorStop(0.4, `rgba(180,90,15,${alpha * 0.6})`);
        grad.addColorStop(1, 'rgba(120,30,0,0)');
        ctx.fillStyle = grad;
        ctx.beginPath();
        ctx.arc(e.x, e.y, e.r * 4, 0, Math.PI * 2);
        ctx.fill();
      }
    };

    function clamp(v: number, a: number, b: number) {
      return Math.max(a, Math.min(b, v));
    }
    function lerp(a: number, b: number, f: number) {
      return a + (b - a) * f;
    }
    function hex(c: string): [number, number, number] {
      const n = parseInt(c.replace('#', ''), 16);
      return [(n >> 16) & 255, (n >> 8) & 255, n & 255];
    }

    render();

    return () => {
      cancelAnimationFrame(raf);
      window.removeEventListener('resize', resize);
    };
  }, [intensity, speed]);

  return <canvas ref={canvasRef} className={`block w-full h-full ${className}`.trim()} />;
}

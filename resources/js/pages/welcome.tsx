import { useEffect, useRef } from 'react';
import gsap from 'gsap';
import SwarmCursor from '@/components/SwarmCursor';
import EvilEye from '@/components/EvilEye';

export default function Welcome() {
    const textRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        const el = textRef.current;
        if (!el) return;

        const targets = el.querySelectorAll<HTMLElement>('[data-slide]');
        const ctx = gsap.context(() => {
            gsap.fromTo(
                targets,
                { y: 90, opacity: 0, filter: 'blur(8px)' },
                {
                    y: 0,
                    opacity: 1,
                    filter: 'blur(0px)',
                    duration: 1.1,
                    ease: 'power3.out',
                    stagger: 0.18,
                    delay: 0.4,
                },
            );

            // "WELCOME" - blinking / pulsing glow
            const blink = el.querySelector<HTMLElement>('[data-blink]');
            if (blink) {
                gsap.to(blink, {
                    opacity: 0.35,
                    textShadow: '0 0 0px rgba(255, 179, 71, 0)',
                    duration: 0.9,
                    ease: 'sine.inOut',
                    yoyo: true,
                    repeat: -1,
                    delay: 1.6,
                });
            }

            // "𝕱𝖑𝖆𝖒𝖒𝖆 𝕻𝖊𝖗𝖕𝖊𝖙𝖛𝖆" - heat wave shimmering across the gradient
            const heat = el.querySelector<HTMLElement>('[data-heat]');
            if (heat) {
                gsap.to(heat, {
                    backgroundPosition: '0% 100%',
                    duration: 2.4,
                    ease: 'sine.inOut',
                    yoyo: true,
                    repeat: -1,
                    delay: 1.6,
                });
            }
        }, el);

        return () => ctx.revert();
    }, []);

    return (
        <div className="relative min-h-screen w-full overflow-hidden bg-[#0a0500]">
            {/* Evil Eye - fiery eye that follows the cursor */}
            <div className="absolute inset-0 z-[5]">
                <EvilEye
                    eyeColor="#FF6F37"
                    intensity={2.0}
                    pupilSize={0.7}
                    irisWidth={0.3}
                    glowIntensity={0.5}
                    scale={0.9}
                    pupilFollow={1}
                    flameSpeed={1.1}
                />
            </div>

            {/* Swarm Cursor - full-screen swarm of fire particles with orange splash */}
            <div className="absolute inset-0 z-10">
                <SwarmCursor
                    color="#b34a00"
                    accentColor="#ffb347"
                    fire
                    count={30}
                    size={6}
                    speed={2.5}
                    spread={130}
                    wander={0.28}
                    trail={0.7}
                    scatterOnClick
                    splash
                    splashColor="#cc6600"
                    opacity={0.85}
                />
            </div>

            {/* Hero text - slides in on entry */}
            <div
                ref={textRef}
                className="pointer-events-none absolute inset-0 z-20 flex flex-col items-center justify-center px-6 text-center"
            >
<div data-slide className="mb-4">
                    <span
                        data-blink
                        className="text-sm font-semibold uppercase tracking-[0.5em] text-black-300"
                    >
                        𝕾𝖆𝖑𝖛𝖊𝖙𝖊
                    </span>
                </div>

                <h1
                    data-slide
                    data-heat
                    className="max-w-4xl bg-[length:200%_200%] text-5xl font-black leading-tight text-transparent sm:text-7xl md:text-8xl"
                    style={{
                        backgroundImage:
                            'linear-gradient(180deg, #ffe0b3 0%, #ffb347 40%, #ff6f37 70%, #cc4400 100%)',
                        WebkitBackgroundClip: 'text',
                        backgroundClip: 'text',
                    }}
                >
                    𝕱𝖑𝖆𝖒𝖒𝖆 𝕻𝖊𝖗𝖕𝖊𝖙𝖚𝖆
                </h1>

                <p
                    data-slide
                    className="mt-6 max-w-xl text-base font-medium text-orange-100/70 sm:text-lg"
                >
                   𝕴𝖌𝖓𝖎𝖘 𝖊𝖘𝖙 𝖋𝖔𝖓𝖘 𝖈𝖆𝖑𝖔𝖗𝖎𝖘 𝖕𝖗𝖔 𝖆𝖓𝖎𝖒𝖆𝖓𝖙𝖎𝖇𝖚𝖘, 𝖊𝖙 𝕾𝖔𝖑 𝖒𝖆𝖝𝖎𝖒𝖆 𝖊𝖎𝖚𝖘 𝖊𝖓𝖊𝖗𝖌𝖎𝖆 𝖊𝖘𝖙.
                </p>
            </div>
        </div>
    );
}

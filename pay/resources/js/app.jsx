import React from 'react';
import { createRoot } from 'react-dom/client';
import CircularText from './components/CircularText';
import EvilEyeBackground from './components/EvilEyeBackground';

const element = document.getElementById('react-app');

if (element) {
    createRoot(element).render(
        <main className="min-h-screen flex items-center justify-center px-6 py-12 bg-slate-950 text-slate-100">
            <div className="w-full max-w-6xl">
                <section className="relative overflow-hidden rounded-[2rem] bg-slate-950/80 border border-cyan-500/10 shadow-2xl shadow-cyan-500/10">
                    <div className="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(34,211,238,0.14),_transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(236,72,153,0.14),_transparent_24%)]" />
                    <div className="relative z-10 grid gap-10 py-14 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
                        <div className="space-y-6 px-6 sm:px-10">
                            <p className="text-sm uppercase tracking-[0.35em] text-cyan-300/70">Evil Eye Command Center</p>
                            <h1 className="text-4xl font-black tracking-tight text-white sm:text-5xl">
                                Neon payment vision with a bold eye of the storm.
                            </h1>
                            <p className="max-w-2xl text-lg leading-8 text-slate-300">
                                A reactive dashboard gateway wrapped in luminous gradients, circular energy, and an animating mystical backdrop.
                            </p>
                            <div className="flex flex-wrap gap-3">
                                <button className="btn-glow inline-flex items-center justify-center rounded-full px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-cyan-500/20">
                                    Explore the dashboard
                                </button>
                                <button className="inline-flex items-center justify-center rounded-full border border-slate-600/70 bg-slate-900/70 px-6 py-3 text-sm font-semibold text-slate-200 transition hover:border-cyan-400/70 hover:text-cyan-300">
                                    Launch insights
                                </button>
                            </div>
                        </div>
                        <div className="px-6 sm:px-10">
                            <div className="evil-eye-bg-wrapper mx-auto max-w-xl">
                                <EvilEyeBackground />
                            </div>
                            <div className="mt-8 text-center text-sm uppercase tracking-[0.35em] text-cyan-300/75">
                                powered by reactive style and subtle neon motion
                            </div>
                        </div>
                    </div>
                </section>
                <div className="mt-14 flex items-center justify-center">
                    <CircularText text="ABUNDANT SPEED • SECURE PAYMENTS • FUTURE READY" />
                </div>
            </div>
        </main>
    );
}

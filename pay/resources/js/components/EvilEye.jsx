import { useEffect, useRef } from "react";
import { Renderer, Camera, Program, Mesh, Triangle, Vec2 } from "ogl";
import "./EvilEye.css";

export default function EvilEye({
    eyeColor = "#FF6F37",
    intensity = 1.5,
    pupilSize = 0.6,
    irisWidth = 0.25,
    glowIntensity = 0.35,
    scale = 0.8,
    noiseScale = 1.0,
    pupilFollow = 1.0,
    flameSpeed = 1.0,
    width = 400,
    height = 400,
    backgroundColor = "#120F17",
}) {
    const ctnDom = useRef(null);

    useEffect(() => {
        const container = ctnDom.current;
        if (!container) return undefined;

        // ─── Create a real canvas element and append to container ────────
        const canvas = document.createElement("canvas");
        canvas.style.width = "100%";
        canvas.style.height = "100%";
        canvas.style.display = "block";
        container.appendChild(canvas);

        // ─── Renderer using ogl ──────────────────────────────────────────
        const renderer = new Renderer({ canvas, width, height });
        const gl = renderer.gl;
        gl.clearColor(0.0, 0.0, 0.0, 1.0);

        const camera = new Camera(gl, { left: -1, right: 1, top: 1, bottom: -1 });
        const scene = new Triangle(gl);

        // ─── Vertex shader ───────────────────────────────────────────────
        const vertex = `
            attribute vec2 uv;
            attribute vec2 position;
            varying vec2 vUv;
            void main() {
                vUv = uv;
                gl_Position = vec4(position, 0.0, 1.0);
            }
        `;

        // ─── Fragment shader (evil fire eye) ─────────────────────────────
        const fragment = `
            precision highp float;

            uniform vec2 uResolution;
            uniform float uTime;
            uniform vec2 uMouse;
            uniform vec3 uEyeColor;
            uniform float uIntensity;
            uniform float uPupilSize;
            uniform float uIrisWidth;
            uniform float uGlowIntensity;
            uniform float uNoiseScale;
            uniform float uPupilFollow;
            uniform float uFlameSpeed;
            uniform vec3 uBackground;

            varying vec2 vUv;

            float hash(float n) {
                return fract(sin(n) * 43758.5453123);
            }

            float noise(vec2 x) {
                vec2 p = floor(x);
                vec2 f = fract(x);
                f = f * f * (3.0 - 2.0 * f);
                float n = p.x + p.y * 57.0;
                return mix(
                    mix(hash(n + 0.0), hash(n + 1.0), f.x),
                    mix(hash(n + 57.0), hash(n + 58.0), f.x),
                    f.y
                );
            }

            float fbm(vec2 p) {
                float f = 0.0;
                f += 0.5000 * noise(p);
                f += 0.2500 * noise(p * 2.02);
                f += 0.1250 * noise(p * 4.04);
                f += 0.0625 * noise(p * 8.08);
                return f / 0.9375;
            }

            void main() {
                vec2 uv = (gl_FragCoord.xy - uResolution * 0.5) / uResolution.y;
                vec2 m = (uMouse - 0.5) * 2.0;

                // Eye shape (almond)
                float aspect = uResolution.x / uResolution.y;
                float eyeT = 0.8;
                float d = length(uv * vec2(1.0 / eyeT, eyeT));
                float r = (0.5 + 0.3 * pow(cos(uv.x * 1.7), 2.0)) * 0.55;
                float eye = smoothstep(0.05, 0.0, d - r);

                // Glow
                float glow = exp(-d * d * (2.0 - uGlowIntensity)) * uGlowIntensity;
                vec3 col = uBackground * 0.0 + uEyeColor * glow * 0.8;

                if (eye > 0.01) {
                    // Pupil offset following mouse
                    vec2 pupilOffset = m * uPupilFollow * 0.35;
                    vec2 p = uv - pupilOffset;

                    // Iris
                    float irisR = r * 0.75;
                    float irisRing = smoothstep(irisR, irisR - irisR * (0.15 + uIrisWidth), length(p));
                    float iris = smoothstep(irisR, irisR * 0.15, length(p));

                    // Sclera
                    float sclera = smoothstep(r, r * 0.6, length(uv)) * eye;
                    col += vec3(1.0, 1.0, 1.0) * sclera * 0.9;

                    // Iris color gradient
                    float irDist = length(p) / irisR;
                    vec3 irisColor = mix(uEyeColor, uEyeColor * 0.35, smoothstep(0.0, 1.0, irDist));
                    col += irisColor * irisRing;

                    // Iris texture / spokes
                    float angle = atan(p.y, p.x);
                    float spokes = abs(sin(angle * 16.0 + noise(p * (8.0 * uNoiseScale)) * 6.0));
                    float tex = fbm(p * (10.0 * uNoiseScale) + vec2(uTime * 0.1, 0.0));
                    col += irisColor * spokes * irisRing * 0.6 + irisColor * tex * 0.5;

                    // Pupil (black base)
                    float pupilR = r * 0.18 * (uPupilSize / 0.6);
                    float pupil = smoothstep(pupilR, pupilR * 0.6, length(p));
                    col = mix(col, vec3(0.0, 0.0, 0.0), pupil * 0.85);

                    // Flame inside pupil
                    float flameN = fbm(p * (14.0 * uNoiseScale) + vec2(0.0, -uTime * uFlameSpeed * 1.2));
                    float flameMask = smoothstep(0.3, 0.8, flameN) * pupil;
                    vec3 flameCol = mix(uEyeColor, vec3(1.0, 0.95, 0.6), smoothstep(0.2, 0.9, flameN));
                    flameCol = mix(flameCol, uEyeColor * 0.6, smoothstep(0.6, 1.2, flameN + sin(uTime * uFlameSpeed) * 0.1));
                    col += flameCol * flameMask * (1.0 - uIntensity * 0.4 + uIntensity * 0.4);

                    // Specular highlight
                    vec2 hl = uv - pupilOffset + vec2(-0.12, -0.15) * r;
                    float spec = smoothstep(0.18, 0.02, length(hl) / r);
                    col += vec3(1.0) * spec * 0.7;

                    // Eye outline
                    float outline = smoothstep(0.0, -0.01, d - r) - smoothstep(-0.02, -0.04, d - r);
                    col += uEyeColor * outline * 0.9;

                    // Eyelid shadow
                    float lidShadow = smoothstep(-0.85, 0.1, uv.y) * (0.15 + 0.15 * sin(uv.x * aspect + uTime * 0.3));
                    col *= 1.0 - lidShadow * 0.4;
                }

                gl_FragColor = vec4(col, 1.0);
            }
        `;

        // ─── Program with uniforms ───────────────────────────────────────
        const program = new Program(gl, {
            vertex,
            fragment,
            uniforms: {
                uResolution: { value: new Vec2(width, height) },
                uTime: { value: 0 },
                uMouse: { value: new Vec2(0.5, 0.5) },
                uEyeColor: { value: new Float32Array(3) },
                uIntensity: { value: intensity },
                uPupilSize: { value: pupilSize },
                uIrisWidth: { value: irisWidth },
                uGlowIntensity: { value: glowIntensity },
                uNoiseScale: { value: noiseScale },
                uPupilFollow: { value: pupilFollow },
                uFlameSpeed: { value: flameSpeed },
                uBackground: { value: new Float32Array(3) },
            },
        });

        const mesh = new Mesh(gl, { geometry: scene, program });

        // ─── Helper to convert hex to rgb array ──────────────────────────
        const hexToRgb = (hex) => {
            const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result
                ? [parseInt(result[1], 16) / 255, parseInt(result[2], 16) / 255, parseInt(result[3], 16) / 255]
                : [1.0, 0.4, 0.2];
        };

        program.uniforms.uEyeColor.value.set(...hexToRgb(eyeColor));
        program.uniforms.uBackground.value.set(...hexToRgb(backgroundColor));

        // ─── Mouse handling ──────────────────────────────────────────────
        const mouseTarget = new Vec2(0.5, 0.5);
        const mouseCurrent = new Vec2(0.5, 0.5);

        const getMousePositionInCanvas = (event) => {
            const rect = canvas.getBoundingClientRect();
            const x = event.touches ? event.touches[0].clientX : event.clientX;
            const y = event.touches ? event.touches[0].clientY : event.clientY;
            return {
                x: (x - rect.left) / rect.width,
                y: 1 - (y - rect.top) / rect.height,
            };
        };

        const handlePointerMove = (event) => {
            const pos = getMousePositionInCanvas(event);
            mouseTarget.set(pos.x, pos.y);
        };

        window.addEventListener("mousemove", handlePointerMove);
        window.addEventListener("touchmove", handlePointerMove);

        // ─── Animation loop ──────────────────────────────────────────────
        let startTime = performance.now();
        let animationId;

        const render = (time) => {
            animationId = requestAnimationFrame(render);

            // Smooth mouse follow
            mouseCurrent.x += (mouseTarget.x - mouseCurrent.x) * 0.08 * pupilFollow;
            mouseCurrent.y += (mouseTarget.y - mouseCurrent.y) * 0.08 * pupilFollow;

            const elapsed = (time - startTime) / 1000;
            program.uniforms.uTime.value = elapsed;
            program.uniforms.uMouse.value.set(mouseCurrent.x, mouseCurrent.y);

            renderer.render({ scene: mesh, camera });
        };
        animationId = requestAnimationFrame(render);

        return () => {
            cancelAnimationFrame(animationId);
            window.removeEventListener("mousemove", handlePointerMove);
            window.removeEventListener("touchmove", handlePointerMove);
            renderer.gl.getExtension("WEBGL_lose_context")?.loseContext();
            if (canvas.parentNode === container) {
                container.removeChild(canvas);
            }
        };
    }, [eyeColor, intensity, pupilSize, irisWidth, glowIntensity, scale, noiseScale, pupilFollow, flameSpeed, width, height, backgroundColor]);

    return (
        <div
            ref={ctnDom}
            className="evil-eye-container"
            style={{
                width: width * scale,
                height: height * scale,
                margin: "0 auto",
                cursor: "crosshair",
            }}
        />
    );
}
</content>

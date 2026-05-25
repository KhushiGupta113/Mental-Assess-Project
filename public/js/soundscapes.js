/**
 * Shared Web Audio API sound generators for MindAssess Modes.
 * Provides procedurally generated ambient sounds (Rain, Nature, Water, Noise).
 */
window.SoundscapeGenerators = {
    // ─── Noise Buffer Helper ───
    _createNoiseBuffer(ctx, type) {
        const bufferSize = 2 * ctx.sampleRate;
        const buffer = ctx.createBuffer(1, bufferSize, ctx.sampleRate);
        const data = buffer.getChannelData(0);

        if (type === 'white') {
            for (let i = 0; i < bufferSize; i++) {
                data[i] = Math.random() * 2 - 1;
            }
        } else if (type === 'brown') {
            let last = 0;
            for (let i = 0; i < bufferSize; i++) {
                const white = Math.random() * 2 - 1;
                data[i] = (last + 0.02 * white) / 1.02;
                last = data[i];
                data[i] *= 3.5;
            }
        } else if (type === 'pink') {
            let b0=0,b1=0,b2=0,b3=0,b4=0,b5=0,b6=0;
            for (let i = 0; i < bufferSize; i++) {
                const white = Math.random() * 2 - 1;
                b0 = 0.99886*b0 + white*0.0555179;
                b1 = 0.99332*b1 + white*0.0750759;
                b2 = 0.96900*b2 + white*0.1538520;
                b3 = 0.86650*b3 + white*0.3104856;
                b4 = 0.55000*b4 + white*0.5329522;
                b5 = -0.7616*b5 - white*0.0168980;
                data[i] = b0+b1+b2+b3+b4+b5+b6+white*0.5362;
                data[i] *= 0.11;
                b6 = white * 0.115926;
            }
        }
        return buffer;
    },

    // ─── Reverb Helper ───
    _createReverbBuffer(ctx, duration = 4, decay = 3) {
        const length = ctx.sampleRate * duration;
        const buffer = ctx.createBuffer(2, length, ctx.sampleRate);
        for (let i = 0; i < 2; i++) {
            const channel = buffer.getChannelData(i);
            for (let j = 0; j < length; j++) {
                channel[j] = (Math.random() * 2 - 1) * Math.pow(1 - j / length, decay);
            }
        }
        return buffer;
    },

    _createNoiseSource(ctx, gain, type, filterOpts) {
        const buffer = this._createNoiseBuffer(ctx, type || 'white');
        const source = ctx.createBufferSource();
        source.buffer = buffer;
        source.loop = true;

        let lastNode = source;
        const nodes = [source];

        if (filterOpts) {
            const filter = ctx.createBiquadFilter();
            filter.type = filterOpts.type || 'bandpass';
            filter.frequency.value = filterOpts.freq || 1000;
            if (filterOpts.Q !== undefined) filter.Q.value = filterOpts.Q;
            lastNode.connect(filter);
            lastNode = filter;
            nodes.push(filter);
        }

        lastNode.connect(gain);
        source.start();

        return {
            nodes,
            stop() {
                try { source.stop(); } catch(e) {}
                nodes.forEach(n => { try { n.disconnect(); } catch(e) {} });
            }
        };
    },

    // ─── Rain ───
    lightRain(ctx, gain) {
        return this._createNoiseSource(ctx, gain, 'white', { type: 'bandpass', freq: 800, Q: 0.5 });
    },

    heavyRain(ctx, gain) {
        const innerGain = ctx.createGain();
        innerGain.gain.value = 1.5;
        innerGain.connect(gain);
        return this._createNoiseSource(ctx, innerGain, 'white', { type: 'bandpass', freq: 600, Q: 0.3 });
    },

    thunder(ctx, gain) {
        const nodes = [];
        let running = true;
        let timeoutIds = [];

        function rumble() {
            if (!running) return;

            const osc = ctx.createOscillator();
            const oscGain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.value = 40 + Math.random() * 40;
            oscGain.gain.setValueAtTime(0, ctx.currentTime);
            oscGain.gain.linearRampToValueAtTime(0.6 + Math.random() * 0.4, ctx.currentTime + 0.3);
            oscGain.gain.linearRampToValueAtTime(0, ctx.currentTime + 2 + Math.random() * 2);
            osc.connect(oscGain);
            oscGain.connect(gain);
            osc.start();
            osc.stop(ctx.currentTime + 4);
            nodes.push(osc, oscGain);

            const buf = ctx.createBuffer(1, ctx.sampleRate * 2, ctx.sampleRate);
            const d = buf.getChannelData(0);
            for (let i = 0; i < d.length; i++) d[i] = (Math.random() * 2 - 1) * 0.3;
            const nSrc = ctx.createBufferSource();
            nSrc.buffer = buf;
            const lp = ctx.createBiquadFilter();
            lp.type = 'lowpass';
            lp.frequency.value = 200;
            const nGain = ctx.createGain();
            nGain.gain.setValueAtTime(0, ctx.currentTime);
            nGain.gain.linearRampToValueAtTime(0.3, ctx.currentTime + 0.2);
            nGain.gain.linearRampToValueAtTime(0, ctx.currentTime + 3);
            nSrc.connect(lp);
            lp.connect(nGain);
            nGain.connect(gain);
            nSrc.start();
            nSrc.stop(ctx.currentTime + 4);
            nodes.push(nSrc, lp, nGain);

            if (running) {
                timeoutIds.push(setTimeout(rumble, 4000 + Math.random() * 8000));
            }
        }
        rumble();

        return {
            nodes,
            stop() {
                running = false;
                timeoutIds.forEach(id => clearTimeout(id));
                nodes.forEach(n => { try { n.disconnect(); } catch(e) {} });
            }
        };
    },

    // ─── Nature ───
    forest(ctx, gain) {
        const buffer = this._createNoiseBuffer(ctx, 'white');
        const source = ctx.createBufferSource();
        source.buffer = buffer;
        source.loop = true;

        const bp = ctx.createBiquadFilter();
        bp.type = 'bandpass';
        bp.frequency.value = 500;
        bp.Q.value = 0.8;

        const lfo = ctx.createOscillator();
        const lfoGain = ctx.createGain();
        lfo.type = 'sine';
        lfo.frequency.value = 0.3;
        lfoGain.gain.value = 200;
        lfo.connect(lfoGain);
        lfoGain.connect(bp.frequency);
        lfo.start();

        const volLfo = ctx.createOscillator();
        const volLfoGain = ctx.createGain();
        volLfo.type = 'sine';
        volLfo.frequency.value = 0.15;
        volLfoGain.gain.value = 0.15;
        volLfo.connect(volLfoGain);
        const modGain = ctx.createGain();
        modGain.gain.value = 0.6;
        volLfoGain.connect(modGain.gain);
        volLfo.start();

        source.connect(bp);
        bp.connect(modGain);
        modGain.connect(gain);
        source.start();

        const nodes = [source, bp, lfo, lfoGain, volLfo, volLfoGain, modGain];
        return {
            nodes,
            stop() {
                try { source.stop(); lfo.stop(); volLfo.stop(); } catch(e) {}
                nodes.forEach(n => { try { n.disconnect(); } catch(e) {} });
            }
        };
    },

    crickets(ctx, gain) {
        const nodes = [];
        let running = true;
        let timeoutIds = [];

        function chirp() {
            if (!running) return;
            const freq = 4000 + Math.random() * 2000;
            const osc = ctx.createOscillator();
            const cGain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.value = freq;
            cGain.gain.setValueAtTime(0, ctx.currentTime);

            const dur = 0.04 + Math.random() * 0.04;
            const repeats = 3 + Math.floor(Math.random() * 4);
            for (let i = 0; i < repeats; i++) {
                const t = ctx.currentTime + i * (dur * 2);
                cGain.gain.setValueAtTime(0, t);
                cGain.gain.linearRampToValueAtTime(0.12, t + dur * 0.3);
                cGain.gain.linearRampToValueAtTime(0, t + dur);
            }

            osc.connect(cGain);
            cGain.connect(gain);
            osc.start();
            osc.stop(ctx.currentTime + repeats * dur * 2 + 0.1);
            nodes.push(osc, cGain);
        }

        function scheduleChirps() {
            if (!running) return;
            chirp();
            timeoutIds.push(setTimeout(scheduleChirps, 200 + Math.random() * 800));
        }
        scheduleChirps();

        return {
            nodes,
            stop() {
                running = false;
                timeoutIds.forEach(id => clearTimeout(id));
                nodes.forEach(n => { try { n.disconnect(); } catch(e) {} });
            }
        };
    },

    wind(ctx, gain) {
        const buffer = this._createNoiseBuffer(ctx, 'brown');
        const source = ctx.createBufferSource();
        source.buffer = buffer;
        source.loop = true;

        const lp = ctx.createBiquadFilter();
        lp.type = 'lowpass';
        lp.frequency.value = 400;

        const lfo = ctx.createOscillator();
        const lfoGain = ctx.createGain();
        lfo.type = 'sine';
        lfo.frequency.value = 0.05;
        lfoGain.gain.value = 300;
        lfo.connect(lfoGain);
        lfoGain.connect(lp.frequency);
        lfo.start();

        source.connect(lp);
        lp.connect(gain);
        source.start();

        return {
            nodes: [source, lp, lfo, lfoGain],
            stop() {
                try { source.stop(); lfo.stop(); } catch(e) {}
                [source, lp, lfo, lfoGain].forEach(n => { try { n.disconnect(); } catch(e) {} });
            }
        };
    },
    
    campfire(ctx, gain) {
        const buffer = this._createNoiseBuffer(ctx, 'brown');
        const source = ctx.createBufferSource();
        source.buffer = buffer;
        source.loop = true;

        const hp = ctx.createBiquadFilter();
        hp.type = 'highpass';
        hp.frequency.value = 1000;
        
        const modGain = ctx.createGain();
        modGain.gain.value = 0.5;

        source.connect(hp);
        hp.connect(modGain);
        modGain.connect(gain);
        source.start();

        const nodes = [source, hp, modGain];
        let running = true;
        let timeoutIds = [];

        function pop() {
            if (!running) return;
            const popOsc = ctx.createOscillator();
            const popGain = ctx.createGain();
            popOsc.type = 'square';
            popOsc.frequency.setValueAtTime(100 + Math.random() * 500, ctx.currentTime);
            popOsc.frequency.exponentialRampToValueAtTime(10, ctx.currentTime + 0.05);
            
            popGain.gain.setValueAtTime(0, ctx.currentTime);
            popGain.gain.linearRampToValueAtTime(0.5, ctx.currentTime + 0.01);
            popGain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.05);
            
            popOsc.connect(popGain);
            popGain.connect(gain);
            popOsc.start();
            popOsc.stop(ctx.currentTime + 0.1);
            nodes.push(popOsc, popGain);
            
            timeoutIds.push(setTimeout(pop, 100 + Math.random() * 800));
        }
        pop();

        return {
            nodes,
            stop() {
                running = false;
                timeoutIds.forEach(id => clearTimeout(id));
                nodes.forEach(n => { try { n.disconnect(); } catch(e) {} });
            }
        };
    },

    // ─── Water ───
    oceanWaves(ctx, gain) {
        const buffer = this._createNoiseBuffer(ctx, 'brown');
        const source = ctx.createBufferSource();
        source.buffer = buffer;
        source.loop = true;

        const lp = ctx.createBiquadFilter();
        lp.type = 'lowpass';
        lp.frequency.value = 400;

        const lfo = ctx.createOscillator();
        const lfoGain = ctx.createGain();
        lfo.type = 'sine';
        lfo.frequency.value = 0.1; // Slow wave
        lfoGain.gain.value = 400;
        lfo.connect(lfoGain);
        lfoGain.connect(lp.frequency);
        lfo.start();

        const volLfo = ctx.createOscillator();
        const volLfoGain = ctx.createGain();
        volLfo.type = 'sine';
        volLfo.frequency.value = 0.1;
        volLfoGain.gain.value = 0.5;
        volLfo.connect(volLfoGain);
        
        const modGain = ctx.createGain();
        modGain.gain.value = 0.5;
        volLfoGain.connect(modGain.gain);
        volLfo.start();

        source.connect(lp);
        lp.connect(modGain);
        modGain.connect(gain);
        source.start();

        return {
            nodes: [source, lp, lfo, lfoGain, volLfo, volLfoGain, modGain],
            stop() {
                try { source.stop(); lfo.stop(); volLfo.stop(); } catch(e) {}
                [source, lp, lfo, lfoGain, volLfo, volLfoGain, modGain].forEach(n => { try { n.disconnect(); } catch(e) {} });
            }
        };
    },
    
    riverStream(ctx, gain) {
        return this._createNoiseSource(ctx, gain, 'pink', { type: 'lowpass', freq: 800 });
    },
    
    waterfall(ctx, gain) {
        return this._createNoiseSource(ctx, gain, 'brown', { type: 'lowpass', freq: 1200 });
    },

    // ─── Musical ───
    singingBowls(ctx, gain) {
        const nodes = [];
        let running = true;
        let timeoutIds = [];
        
        // Huge Reverb for premium ambient feel
        const convolver = ctx.createConvolver();
        convolver.buffer = this._createReverbBuffer(ctx, 6, 2);
        const reverbGain = ctx.createGain();
        reverbGain.gain.value = 1.5;
        convolver.connect(reverbGain);
        reverbGain.connect(gain);
        
        // Deep fundamental bowls (Tibetan style)
        const freqs = [110, 146.83, 164.81, 220]; // A2, D3, E3, A3

        function strike() {
            if (!running) return;
            const freq = freqs[Math.floor(Math.random() * freqs.length)];
            
            // Complex harmonics
            const harmonics = [1, 2.76, 5.4, 8.93];
            const oscNodes = [];
            
            harmonics.forEach((mult, index) => {
                const osc = ctx.createOscillator();
                const oscGain = ctx.createGain();
                
                osc.type = index === 0 ? 'sine' : 'triangle';
                osc.frequency.value = freq * mult + (Math.random() * 2 - 1); // slight detune
                
                // Very slow attack, incredibly long release
                const attack = 2 + Math.random() * 2;
                const release = 8 + Math.random() * 5;
                const vol = index === 0 ? 0.3 : (0.1 / index);
                
                oscGain.gain.setValueAtTime(0, ctx.currentTime);
                oscGain.gain.linearRampToValueAtTime(vol, ctx.currentTime + attack);
                oscGain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + attack + release);
                
                osc.connect(oscGain);
                oscGain.connect(convolver); // Route through massive reverb
                oscGain.connect(gain);      // And direct
                
                osc.start();
                osc.stop(ctx.currentTime + attack + release + 0.1);
                
                oscNodes.push(osc, oscGain);
            });
            
            nodes.push(...oscNodes);
            timeoutIds.push(setTimeout(strike, 6000 + Math.random() * 8000));
        }
        
        strike();
        timeoutIds.push(setTimeout(strike, 4000)); // second bowl overlapping

        return {
            nodes,
            stop() {
                running = false;
                timeoutIds.forEach(id => clearTimeout(id));
                nodes.forEach(n => { try { n.disconnect(); } catch(e) {} });
                try { convolver.disconnect(); reverbGain.disconnect(); } catch(e) {}
            }
        };
    },

    binauralBeats(ctx, gain) {
        // Renamed internally but keeping the ID 'binauralBeats' to avoid breaking presets.
        // This is actually an ETHEREAL AMBIENT PAD.
        const nodes = [];
        
        const convolver = ctx.createConvolver();
        convolver.buffer = this._createReverbBuffer(ctx, 5, 1.5);
        convolver.connect(gain);

        // Subtractive synthesis pad: 3 detuned sawtooths through a low-pass filter
        const filter = ctx.createBiquadFilter();
        filter.type = 'lowpass';
        filter.frequency.value = 300; // Deep, muffled
        filter.Q.value = 1;
        
        // Slow LFO for the filter
        const lfo = ctx.createOscillator();
        const lfoGain = ctx.createGain();
        lfo.type = 'sine';
        lfo.frequency.value = 0.05; // Extremely slow sweep
        lfoGain.gain.value = 200;
        lfo.connect(lfoGain);
        lfoGain.connect(filter.frequency);
        lfo.start();
        nodes.push(lfo, lfoGain);
        
        filter.connect(convolver);
        filter.connect(gain);
        
        // Solfeggio 432Hz root
        const baseFreq = 108; // 432 / 4 (deep bass)
        const detunes = [-6, 0, 8, 1200]; // 1 octave up
        
        const oscs = [];
        detunes.forEach(detune => {
            const osc = ctx.createOscillator();
            const oscGain = ctx.createGain();
            
            osc.type = 'sawtooth';
            osc.frequency.value = baseFreq;
            osc.detune.value = detune;
            
            // Very soft volume
            oscGain.gain.value = 0.05;
            
            osc.connect(oscGain);
            oscGain.connect(filter);
            
            osc.start();
            oscs.push(osc, oscGain);
        });
        
        nodes.push(filter, ...oscs);

        return {
            nodes,
            stop() {
                oscs.forEach(n => { try { if (n.stop) n.stop(); n.disconnect(); } catch(e) {} });
                nodes.forEach(n => { try { n.disconnect(); } catch(e) {} });
                try { convolver.disconnect(); } catch(e) {}
            }
        };
    },

    piano(ctx, gain) {
        const nodes = [];
        let running = true;
        let timeoutIds = [];
        
        // Reverb
        const convolver = ctx.createConvolver();
        convolver.buffer = this._createReverbBuffer(ctx, 3, 2);
        convolver.connect(gain);
        
        // Pentatonic scale
        const freqs = [261.63, 293.66, 329.63, 392.00, 440.00, 523.25];

        function playNote() {
            if (!running) return;
            const freq = freqs[Math.floor(Math.random() * freqs.length)];
            
            // FM Piano: Carrier + Modulator
            const carrier = ctx.createOscillator();
            const modulator = ctx.createOscillator();
            const modGain = ctx.createGain();
            const oscGain = ctx.createGain();
            
            carrier.type = 'sine';
            modulator.type = 'triangle';
            
            carrier.frequency.value = freq;
            modulator.frequency.value = freq * 2; // Octave above for hammer transient
            
            // Hammer strike modulation envelope
            modGain.gain.setValueAtTime(freq * 1.5, ctx.currentTime);
            modGain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.2);
            
            // String decay envelope
            oscGain.gain.setValueAtTime(0, ctx.currentTime);
            oscGain.gain.linearRampToValueAtTime(0.2, ctx.currentTime + 0.02);
            oscGain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 4);
            
            modulator.connect(modGain);
            modGain.connect(carrier.frequency);
            
            carrier.connect(oscGain);
            oscGain.connect(convolver);
            oscGain.connect(gain);
            
            modulator.start();
            carrier.start();
            modulator.stop(ctx.currentTime + 4.1);
            carrier.stop(ctx.currentTime + 4.1);
            
            nodes.push(carrier, modulator, modGain, oscGain);
            timeoutIds.push(setTimeout(playNote, 2500 + Math.random() * 4000));
        }
        
        playNote();

        return {
            nodes,
            stop() {
                running = false;
                timeoutIds.forEach(id => clearTimeout(id));
                nodes.forEach(n => { try { n.disconnect(); } catch(e) {} });
                try { convolver.disconnect(); } catch(e) {}
            }
        };
    },

    handpan(ctx, gain) {
        const nodes = [];
        let running = true;
        let timeoutIds = [];
        
        // Reverb
        const convolver = ctx.createConvolver();
        convolver.buffer = this._createReverbBuffer(ctx, 4, 2);
        convolver.connect(gain);
        
        // D minor scale
        const freqs = [293.66, 329.63, 349.23, 440.00, 493.88];

        function strike() {
            if (!running) return;
            const freq = freqs[Math.floor(Math.random() * freqs.length)];
            
            // Metallic FM bell
            const carrier = ctx.createOscillator();
            const modulator = ctx.createOscillator();
            const modGain = ctx.createGain();
            const envGain = ctx.createGain();
            
            carrier.type = 'sine';
            modulator.type = 'sine';
            
            carrier.frequency.value = freq;
            modulator.frequency.value = freq * 1.5; 
            
            modGain.gain.setValueAtTime(freq * 3, ctx.currentTime);
            modGain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 1);
            
            envGain.gain.setValueAtTime(0, ctx.currentTime);
            envGain.gain.linearRampToValueAtTime(0.3, ctx.currentTime + 0.01);
            envGain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 3);
            
            modulator.connect(modGain);
            modGain.connect(carrier.frequency);
            carrier.connect(envGain);
            envGain.connect(convolver);
            envGain.connect(gain);
            
            modulator.start();
            carrier.start();
            modulator.stop(ctx.currentTime + 3.1);
            carrier.stop(ctx.currentTime + 3.1);
            
            nodes.push(carrier, modulator, modGain, envGain);
            timeoutIds.push(setTimeout(strike, 1500 + Math.random() * 2500));
        }
        
        strike();

        return {
            nodes,
            stop() {
                running = false;
                timeoutIds.forEach(id => clearTimeout(id));
                nodes.forEach(n => { try { n.disconnect(); } catch(e) {} });
                try { convolver.disconnect(); } catch(e) {}
            }
        };
    },

    flute(ctx, gain) {
        const nodes = [];
        let running = true;
        let timeoutIds = [];
        
        const freqs = [392.00, 440.00, 493.88, 523.25, 587.33];

        function playBreath() {
            if (!running) return;
            const freq = freqs[Math.floor(Math.random() * freqs.length)];
            const osc = ctx.createOscillator();
            const envGain = ctx.createGain();
            
            // Flute is a sine with some vibrato
            osc.type = 'sine';
            osc.frequency.value = freq;
            
            const lfo = ctx.createOscillator();
            const lfoGain = ctx.createGain();
            lfo.type = 'sine';
            lfo.frequency.value = 5; // 5Hz vibrato
            lfoGain.gain.value = 5; // Pitch variation
            
            lfo.connect(lfoGain);
            lfoGain.connect(osc.frequency);
            
            // Breath envelope
            envGain.gain.setValueAtTime(0, ctx.currentTime);
            envGain.gain.linearRampToValueAtTime(0.2, ctx.currentTime + 1); // Slow attack
            envGain.gain.linearRampToValueAtTime(0, ctx.currentTime + 4); // Slow release
            
            osc.connect(envGain);
            envGain.connect(gain);
            
            lfo.start();
            osc.start();
            lfo.stop(ctx.currentTime + 4.1);
            osc.stop(ctx.currentTime + 4.1);
            
            nodes.push(osc, lfo, lfoGain, envGain);
            timeoutIds.push(setTimeout(playBreath, 5000 + Math.random() * 4000));
        }
        
        playBreath();

        return {
            nodes,
            stop() {
                running = false;
                timeoutIds.forEach(id => clearTimeout(id));
                nodes.forEach(n => { try { n.disconnect(); } catch(e) {} });
            }
        };
    },

    // ─── Focus / Noise ───
    whiteNoise(ctx, gain) {
        return this._createNoiseSource(ctx, gain, 'white');
    },
    brownNoise(ctx, gain) {
        return this._createNoiseSource(ctx, gain, 'brown');
    },
    pinkNoise(ctx, gain) {
        return this._createNoiseSource(ctx, gain, 'pink');
    },

    // ─── External Media ───
    externalAudio(ctx, gain, url) {
        const nodes = [];
        let running = true;
        let sourceNode = null;

        // Fetch and decode asynchronously
        fetch(url)
            .then(res => res.arrayBuffer())
            .then(arrayBuffer => ctx.decodeAudioData(arrayBuffer))
            .then(audioBuffer => {
                if (!running) return; // Abort if stopped before loading finished
                sourceNode = ctx.createBufferSource();
                sourceNode.buffer = audioBuffer;
                sourceNode.loop = true; // Infinite loop for background tracks
                sourceNode.connect(gain);
                sourceNode.start(0);
                nodes.push(sourceNode);
            })
            .catch(err => console.error("Soundscapes: Failed to load external audio:", url, err));

        return {
            nodes,
            stop() {
                running = false;
                if (sourceNode) {
                    try { sourceNode.stop(); sourceNode.disconnect(); } catch(e) {}
                }
            }
        };
    }
};

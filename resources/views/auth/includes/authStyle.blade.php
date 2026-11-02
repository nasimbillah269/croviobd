<style>
    .au-page {
        --au-primary: #ff005e; --au-text: #111827; --au-muted: #6b7280; --au-border: #e5e7eb;
        min-height: calc(100vh - 220px);
        display: flex; align-items: center;
        padding: 40px 0 60px;
        background:
            radial-gradient(circle at 10% 10%, rgba(255, 0, 94, .06), transparent 40%),
            radial-gradient(circle at 90% 90%, rgba(255, 61, 132, .06), transparent 40%),
            #f7f7f9;
    }
    .au-shell {
        display: grid; grid-template-columns: 1fr 1.1fr;
        max-width: 940px; margin: 0 auto; overflow: hidden;
        background: #fff; border-radius: 20px;
        box-shadow: 0 24px 60px rgba(17, 24, 39, .12);
        animation: auIn .5s cubic-bezier(.2, .8, .2, 1) both;
    }

    /* brand side */
    .au-brand {
        position: relative; overflow: hidden;
        padding: 44px 40px; color: #fff;
        background: linear-gradient(150deg, #ff005e 0%, #ff3d84 55%, #c2004a 100%);
        display: flex; flex-direction: column; justify-content: space-between;
    }
    .au-brand:before, .au-brand:after {
        content: ''; position: absolute; border-radius: 50%; background: rgba(255, 255, 255, .09);
    }
    .au-brand:before { width: 320px; height: 320px; right: -120px; top: -100px; }
    .au-brand:after { width: 220px; height: 220px; left: -80px; bottom: -90px; }
    .au-brand > * { position: relative; z-index: 1; }
    .au-logo img { max-height: 46px; max-width: 170px; filter: drop-shadow(0 2px 6px rgba(0, 0, 0, .15)); }
    .au-brand h2 { font-size: 28px; font-weight: 700; line-height: 1.25; margin: 34px 0 10px; color: #fff; }
    .au-brand > div > p { font-size: 15px; opacity: .9; margin: 0 0 26px; }
    .au-perks { list-style: none; padding: 0; margin: 0; }
    .au-perks li { display: flex; align-items: center; gap: 12px; font-size: 14.5px; margin-bottom: 14px; }
    .au-perks i {
        width: 34px; height: 34px; border-radius: 10px; flex-shrink: 0;
        display: inline-flex; align-items: center; justify-content: center;
        background: rgba(255, 255, 255, .18); font-size: 15px;
    }
    .au-brand small { font-size: 12.5px; opacity: .75; }

    /* form side */
    .au-form { padding: 44px 44px 36px; color: var(--au-text); }
    .au-form h1 { font-size: 26px; font-weight: 700; margin: 0 0 6px; }
    .au-sub { color: var(--au-muted); font-size: 14.5px; margin: 0 0 26px; }
    .au-sub a, .au-link { color: var(--au-primary) !important; font-weight: 600; text-decoration: none !important; }
    .au-sub a:hover, .au-link:hover { text-decoration: underline !important; }

    .au-field { margin-bottom: 18px; }
    .au-label-row { display: flex; justify-content: space-between; align-items: baseline; gap: 10px; margin-bottom: 7px; }
    .au-field label, .au-label-row label { display: block; font-size: 14px; font-weight: 600; color: #374151; margin: 0; }
    .au-label-row a { font-size: 13px; }
    .au-input { position: relative; }
    .au-input > i:first-child {
        position: absolute; left: 15px; top: 50%; transform: translateY(-50%);
        color: #9ca3af; font-size: 15px; pointer-events: none; transition: color .15s;
    }
    .au-input input {
        width: 100%; height: 50px; padding: 0 46px 0 44px;
        border: 1.5px solid var(--au-border); border-radius: 12px; background: #fafafa;
        font-size: 15px; color: var(--au-text); transition: border-color .15s, box-shadow .15s, background .15s;
    }
    .au-input input::placeholder { color: #9ca3af; }
    .au-input input:focus { outline: none; border-color: var(--au-primary); background: #fff; box-shadow: 0 0 0 4px rgba(255, 0, 94, .1); }
    .au-input:focus-within > i:first-child { color: var(--au-primary); }
    .au-input.has-error input { border-color: #ef4444; background: #fff; }
    .au-eye {
        position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
        width: 36px; height: 36px; border: 0; border-radius: 8px; background: transparent;
        color: #9ca3af; cursor: pointer; display: flex; align-items: center; justify-content: center;
    }
    .au-eye:hover { color: var(--au-text); background: #f3f4f6; }
    .au-error { display: flex; align-items: center; gap: 6px; color: #dc2626; font-size: 13px; margin-top: 6px; }
    .au-alert {
        display: flex; gap: 10px; align-items: flex-start;
        background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;
        border-radius: 12px; padding: 12px 14px; font-size: 14px; margin-bottom: 20px;
    }
    .au-alert i { margin-top: 2px; }
    .au-form .alert { border-radius: 12px; font-size: 14px; }
    .au-note { font-size: 13px; color: var(--au-muted); line-height: 1.6; margin: 4px 0 20px; }

    .au-submit {
        position: relative; overflow: hidden;
        width: 100%; height: 52px; border: 0; border-radius: 12px; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        background: linear-gradient(120deg, #ff005e 0%, #ff3d84 100%); color: #fff;
        font-size: 16px; font-weight: 700; letter-spacing: .2px;
        box-shadow: 0 10px 24px rgba(255, 0, 94, .3); transition: transform .2s ease, box-shadow .2s ease;
    }
    .au-submit i { transition: transform .2s ease; }
    .au-submit:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(255, 0, 94, .4); }
    .au-submit:hover i { transform: translateX(4px); }
    .au-submit:disabled { opacity: .75; cursor: wait; transform: none; }

    .au-switch { text-align: center; margin: 24px 0 0; padding-top: 20px; border-top: 1px solid #f1f2f4; font-size: 14.5px; color: var(--au-muted); }

    @keyframes auIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: none; } }

    @media (max-width: 767.98px) {
        .au-page { padding: 20px 0 40px; align-items: flex-start; }
        .au-shell { grid-template-columns: 1fr; border-radius: 16px; }
        .au-brand { padding: 26px 22px; }
        .au-brand h2 { font-size: 21px; margin: 18px 0 4px; }
        .au-brand > div > p { margin-bottom: 0; }
        .au-perks, .au-brand small { display: none; }
        .au-form { padding: 28px 20px 24px; }
        .au-form h1 { font-size: 22px; }
    }
    @media (prefers-reduced-motion: reduce) { .au-shell { animation: none; } }
</style>

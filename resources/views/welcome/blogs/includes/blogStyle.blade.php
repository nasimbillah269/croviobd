<style>
    .blog-page{--bl-accent:#34a853;--bl-accent-dark:#2a8a43;--bl-text:#1f2933;--bl-muted:#6b7280;--bl-border:#e5e7eb;--bl-soft:#f5f7f9;background:var(--bl-soft);padding:0 0 60px;color:var(--bl-text);}
    .blog-page a{text-decoration:none;}
    .blog-page img{max-width:100%;}

    /* Header */
    .blog-hero{background:linear-gradient(135deg,#1f2933 0%,#2a5c46 100%);color:#fff;padding:48px 0 40px;margin-bottom:40px;}
    .blog-hero h1{font-size:34px;font-weight:700;margin:0 0 10px;color:#fff;line-height:1.25;}
    .blog-hero p{margin:0;color:rgba(255,255,255,.75);font-size:15px;}
    .blog-crumb{list-style:none;padding:0;margin:0 0 14px;display:flex;flex-wrap:wrap;gap:6px;font-size:13px;color:rgba(255,255,255,.7);}
    .blog-crumb a{color:rgba(255,255,255,.85);}
    .blog-crumb a:hover{color:#fff;}
    .blog-crumb li+li:before{content:"/";margin-right:6px;opacity:.6;}

    /* Toolbar */
    .blog-toolbar{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:16px;margin-bottom:28px;}
    .blog-chips{display:flex;flex-wrap:wrap;gap:8px;}
    .blog-chip{display:inline-block;padding:6px 14px;border-radius:999px;background:#fff;border:1px solid var(--bl-border);color:var(--bl-text);font-size:13px;transition:.2s;}
    .blog-chip:hover,.blog-chip.active{background:var(--bl-accent);border-color:var(--bl-accent);color:#fff;}
    .blog-search{display:flex;background:#fff;border:1px solid var(--bl-border);border-radius:999px;overflow:hidden;min-width:260px;}
    .blog-search input{flex:1;border:0;outline:0;padding:9px 16px;font-size:14px;background:transparent;}
    .blog-search button{border:0;background:var(--bl-accent);color:#fff;padding:0 18px;cursor:pointer;}
    .blog-search button:hover{background:var(--bl-accent-dark);}

    /* Grid + card */
    .blog-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:28px;}
    .blog-card{background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 2px 10px rgba(16,24,40,.06);display:flex;flex-direction:column;transition:transform .25s,box-shadow .25s;height:100%;}
    .blog-card:hover{transform:translateY(-5px);box-shadow:0 14px 30px rgba(16,24,40,.12);}
    .blog-card-img{position:relative;display:block;aspect-ratio:16/10;overflow:hidden;background:var(--bl-soft);}
    .blog-card-img img{width:100%;height:100%;object-fit:cover;transition:transform .4s;}
    .blog-card:hover .blog-card-img img{transform:scale(1.06);}
    .blog-card-date{position:absolute;top:14px;left:14px;background:#fff;border-radius:10px;padding:6px 10px;text-align:center;line-height:1.1;font-size:12px;color:var(--bl-muted);box-shadow:0 2px 8px rgba(0,0,0,.12);text-transform:uppercase;}
    .blog-card-date b{display:block;font-size:20px;color:var(--bl-accent);}
    .blog-card-body{padding:20px 22px 22px;display:flex;flex-direction:column;flex:1;}
    .blog-card-meta{display:flex;flex-wrap:wrap;gap:14px;font-size:13px;color:var(--bl-muted);margin-bottom:10px;}
    .blog-card-meta a{color:var(--bl-muted);}
    .blog-card-meta a:hover{color:var(--bl-accent);}
    .blog-card-meta i{color:var(--bl-accent);margin-right:4px;}
    .blog-card-title{font-size:19px;font-weight:600;line-height:1.4;margin:0 0 10px;}
    .blog-card-title a{color:var(--bl-text);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
    .blog-card-title a:hover{color:var(--bl-accent);}
    .blog-card-text{color:var(--bl-muted);font-size:14px;line-height:1.65;margin:0 0 18px;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;}
    .blog-card-btn{margin-top:auto;align-self:flex-start;display:inline-flex;align-items:center;gap:8px;padding:9px 20px;border-radius:999px;background:var(--bl-accent);color:#fff!important;font-size:14px;font-weight:500;transition:.2s;}
    .blog-card-btn:hover{background:var(--bl-accent-dark);gap:12px;}
    .blog-empty{background:#fff;border-radius:14px;padding:60px 20px;text-align:center;color:var(--bl-muted);}
    .blog-empty i{font-size:40px;color:var(--bl-border);display:block;margin-bottom:12px;}

    /* Pagination */
    .blog-pagination{margin-top:40px;text-align:center;}
    .blog-pagination ul.pager{display:inline-flex;flex-wrap:wrap;gap:6px;padding:0;margin:0;list-style:none;}
    .blog-pagination ul.pager li{padding:0!important;}
    .blog-pagination ul.pager li a,.blog-pagination ul.pager li span{display:block;min-width:40px;padding:8px 14px;border-radius:8px;background:#fff;border:1px solid var(--bl-border);color:var(--bl-text);font-size:14px;}
    .blog-pagination ul.pager li a:hover{border-color:var(--bl-accent);color:var(--bl-accent);}
    .blog-pagination ul.pager li.active span{background:var(--bl-accent);border-color:var(--bl-accent);color:#fff;}
    .blog-pagination ul.pager li.disabled span{opacity:.5;}

    /* Detail */
    .blog-detail-layout{display:grid;grid-template-columns:minmax(0,1fr) 320px;gap:32px;align-items:start;}
    .blog-box{background:#fff;border-radius:14px;box-shadow:0 2px 10px rgba(16,24,40,.06);padding:28px;margin-bottom:28px;}
    .blog-article{padding:0;overflow:hidden;}
    .blog-article-img{width:100%;max-height:480px;object-fit:cover;display:block;}
    .blog-article-body{padding:32px;}
    .blog-detail-meta{display:flex;flex-wrap:wrap;gap:18px;font-size:14px;color:rgba(255,255,255,.8);margin-top:14px;}
    .blog-detail-meta a{color:rgba(255,255,255,.9);}
    .blog-detail-meta i{margin-right:6px;}
    .blog-hero .blog-chip{background:rgba(255,255,255,.12);border-color:rgba(255,255,255,.25);color:#fff;}
    .blog-hero .blog-chip:hover{background:var(--bl-accent);border-color:var(--bl-accent);}
    .blog-lead{font-size:18px;line-height:1.7;color:#374151;border-left:4px solid var(--bl-accent);padding:4px 0 4px 18px;margin-bottom:26px;}
    .blog-content{font-size:16px;line-height:1.85;color:#374151;word-wrap:break-word;}
    .blog-content img{height:auto;border-radius:10px;margin:10px 0;}
    .blog-content h1,.blog-content h2,.blog-content h3,.blog-content h4{color:var(--bl-text);margin:28px 0 12px;font-weight:600;}
    .blog-content p{margin:0 0 16px;}
    .blog-content ul,.blog-content ol{padding-left:22px;margin-bottom:16px;}
    .blog-content blockquote{background:var(--bl-soft);border-left:4px solid var(--bl-accent);padding:14px 18px;border-radius:0 8px 8px 0;margin:20px 0;}
    .blog-content table{width:100%;border-collapse:collapse;margin-bottom:16px;}
    .blog-content table td,.blog-content table th{border:1px solid var(--bl-border);padding:8px;}
    .blog-article-footer{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:16px;border-top:1px solid var(--bl-border);margin-top:30px;padding-top:22px;}
    .blog-tags{display:flex;flex-wrap:wrap;align-items:center;gap:8px;}
    .blog-tags strong,.blog-share strong{font-size:14px;margin-right:4px;}
    .blog-tags a{padding:4px 12px;border-radius:6px;background:var(--bl-soft);color:var(--bl-muted);font-size:13px;}
    .blog-tags a:hover{background:var(--bl-accent);color:#fff;}
    .blog-share{display:flex;align-items:center;gap:8px;}
    .blog-share a{width:36px;height:36px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:15px;transition:transform .2s;}
    .blog-share a:hover{transform:translateY(-3px);color:#fff;}
    .blog-share .fb{background:#1877f2;}.blog-share .tw{background:#111;}.blog-share .wa{background:#25d366;}.blog-share .in{background:#0a66c2;}

    .blog-nav{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:28px;}
    .blog-nav a{background:#fff;border-radius:14px;padding:18px 22px;box-shadow:0 2px 10px rgba(16,24,40,.06);color:var(--bl-text);display:block;transition:.2s;}
    .blog-nav a:hover{box-shadow:0 10px 24px rgba(16,24,40,.1);color:var(--bl-accent);}
    .blog-nav small{display:block;color:var(--bl-muted);font-size:12px;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;}
    .blog-nav span{font-weight:600;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;}
    .blog-nav .next{text-align:right;grid-column:2;}

    .blog-section-title{font-size:22px;font-weight:700;margin:0 0 20px;position:relative;padding-bottom:10px;}
    .blog-section-title:after{content:"";position:absolute;left:0;bottom:0;width:44px;height:3px;border-radius:3px;background:var(--bl-accent);}

    .blog-comment{display:flex;gap:14px;padding:18px 0;border-bottom:1px solid var(--bl-border);}
    .blog-comment:last-child{border-bottom:0;}
    .blog-avatar{flex:0 0 46px;height:46px;border-radius:50%;background:var(--bl-accent);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:18px;text-transform:uppercase;}
    .blog-comment h5{font-size:15px;font-weight:600;margin:0 0 2px;}
    .blog-comment small{color:var(--bl-muted);font-size:12px;}
    .blog-comment p{margin:8px 0 0;color:#374151;font-size:14px;line-height:1.6;}
    .blog-form .row-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;}
    .blog-form input,.blog-form textarea{width:100%;border:1px solid var(--bl-border);border-radius:10px;padding:11px 14px;font-size:14px;outline:0;background:#fff;}
    .blog-form input:focus,.blog-form textarea:focus{border-color:var(--bl-accent);box-shadow:0 0 0 3px rgba(52,168,83,.15);}
    .blog-form textarea{min-height:130px;resize:vertical;margin-bottom:14px;}
    .blog-form button{border:0;cursor:pointer;}
    .blog-form .err{color:#dc2626;font-size:12px;margin-top:4px;display:block;}
    .blog-alert{background:#ecfdf3;color:#166534;border:1px solid #bbf7d0;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:14px;}

    /* Sidebar */
    .blog-sidebar{position:sticky;top:20px;}
    .blog-sidebar .blog-box{padding:22px;}
    .blog-sidebar h3{font-size:17px;font-weight:700;margin:0 0 16px;}
    .blog-sidebar .blog-search{min-width:0;}
    .blog-cat-list{list-style:none;padding:0;margin:0;}
    .blog-cat-list li a{display:flex;align-items:center;gap:8px;padding:9px 0;border-bottom:1px dashed var(--bl-border);color:var(--bl-text);font-size:14px;}
    .blog-cat-list li:last-child a{border-bottom:0;}
    .blog-cat-list li a:hover{color:var(--bl-accent);padding-left:4px;}
    .blog-cat-list i{color:var(--bl-accent);}
    .blog-recent{display:flex;gap:12px;align-items:center;margin-bottom:14px;}
    .blog-recent:last-child{margin-bottom:0;}
    .blog-recent img{width:70px;height:60px;object-fit:cover;border-radius:8px;flex:0 0 70px;}
    .blog-recent h4{font-size:14px;font-weight:600;margin:0 0 4px;line-height:1.4;}
    .blog-recent h4 a{color:var(--bl-text);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
    .blog-recent h4 a:hover{color:var(--bl-accent);}
    .blog-recent small{color:var(--bl-muted);font-size:12px;}
    .blog-related .blog-grid{gap:20px;}
    .blog-related .blog-card-title{font-size:16px;}

    @media (max-width:991px){
        .blog-grid{grid-template-columns:repeat(2,minmax(0,1fr));}
        .blog-detail-layout{grid-template-columns:1fr;}
        .blog-sidebar{position:static;}
    }
    @media (max-width:575px){
        .blog-grid,.blog-related .blog-grid{grid-template-columns:1fr;}
        .blog-hero{padding:32px 0 28px;margin-bottom:24px;}
        .blog-hero h1{font-size:24px;}
        .blog-article-body,.blog-box{padding:20px;}
        .blog-form .row-2,.blog-nav{grid-template-columns:1fr;}
        .blog-nav .next{grid-column:auto;}
        .blog-search{min-width:0;width:100%;}
    }
</style>

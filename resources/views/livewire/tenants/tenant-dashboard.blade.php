<div
    data-animate="reveal"
    style="opacity:0"
    x-data="dashboard()"
    x-init="init()"
>

{{-- ── Page Header ── --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.75rem;gap:1rem;flex-wrap:wrap;">
    <div>
        <h1 style="font-family:'Instrument Serif',serif;font-size:1.75rem;font-style:italic;font-weight:400;line-height:1.2;color:#f1f5f9;">
            Bonjour, <span style="background:linear-gradient(135deg,#6366f1,#10b981);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">{{ Auth::guard('tenant')->user()?->name ?? 'Directeur' }}</span> 👋
        </h1>
        <p style="font-size:.82rem;color:#94a3b8;margin-top:.3rem;">
            Voici un aperçu de votre établissement — Année scolaire <span style="color:#10b981;font-family:'DM Mono',monospace;">{{$tenant_dashboard_selected_school_year}}</span>
        </p>
    </div>
    <div style="display:flex;gap:.6rem;flex-wrap:wrap;">
        <button style="display:flex;align-items:center;gap:.4rem;padding:.45rem .9rem;background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.25);border-radius:.5rem;color:#818cf8;font-size:.78rem;font-weight:600;cursor:pointer;transition:all .2s;" onmouseover="this.style.background='rgba(99,102,241,.18)'" onmouseout="this.style.background='rgba(99,102,241,.1)'">
            📤 Exporter
        </button>
        <button style="display:flex;align-items:center;gap:.4rem;padding:.45rem .9rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);border:none;border-radius:.5rem;color:white;font-size:.78rem;font-weight:600;cursor:pointer;transition:all .2s;" onmouseover="this.style.opacity='.9'" onmouseout="this.style.opacity='1'">
            ➕ Nouvelle action
        </button>
    </div>
</div>

{{-- ── KPI Cards ── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.75rem;">

    {{-- Apprenants --}}
    <div class="kpi-card" data-animate="card" style="opacity:0;background:#1e293b;border:1px solid #334155;border-radius:.875rem;padding:1.25rem;position:relative;overflow:hidden;transition:border-color .2s,transform .2s;" onmouseover="this.style.borderColor='rgba(99,102,241,.4)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='#334155';this.style.transform='translateY(0)'">
        <div style="position:absolute;top:1rem;right:1rem;font-size:1.5rem;opacity:.15;">👨‍🎓</div>
        <div style="font-family:'DM Mono',monospace;font-size:.62rem;color:#64748b;text-transform:uppercase;letter-spacing:.1em;margin-bottom:.5rem;">Apprenants</div>
        <div style="font-family:'Instrument Serif',serif;font-size:2.2rem;font-weight:400;color:#f1f5f9;line-height:1;" x-text="kpi.students">847</div>
        <div style="display:flex;align-items:center;gap:.35rem;margin-top:.6rem;">
            <span style="font-size:.7rem;color:#10b981;background:rgba(16,185,129,.1);padding:.1rem .45rem;border-radius:100px;border:1px solid rgba(16,185,129,.2);">↑ +12</span>
            <span style="font-size:.7rem;color:#64748b;">vs année passée</span>
        </div>
    </div>

    {{-- Enseignants --}}
    <div class="kpi-card" data-animate="card" style="opacity:0;background:#1e293b;border:1px solid #334155;border-radius:.875rem;padding:1.25rem;position:relative;overflow:hidden;transition:border-color .2s,transform .2s;" onmouseover="this.style.borderColor='rgba(16,185,129,.4)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='#334155';this.style.transform='translateY(0)'">
        <div style="position:absolute;top:1rem;right:1rem;font-size:1.5rem;opacity:.15;">👩‍🏫</div>
        <div style="font-family:'DM Mono',monospace;font-size:.62rem;color:#64748b;text-transform:uppercase;letter-spacing:.1em;margin-bottom:.5rem;">Enseignants</div>
        <div style="font-family:'Instrument Serif',serif;font-size:2.2rem;font-weight:400;color:#f1f5f9;line-height:1;" x-text="kpi.teachers">42</div>
        <div style="display:flex;align-items:center;gap:.35rem;margin-top:.6rem;">
            <span style="font-size:.7rem;color:#10b981;background:rgba(16,185,129,.1);padding:.1rem .45rem;border-radius:100px;border:1px solid rgba(16,185,129,.2);">↑ +3</span>
            <span style="font-size:.7rem;color:#64748b;">actifs cette année</span>
        </div>
    </div>

    {{-- Classes --}}
    <div class="kpi-card" data-animate="card" style="opacity:0;background:#1e293b;border:1px solid #334155;border-radius:.875rem;padding:1.25rem;position:relative;overflow:hidden;transition:border-color .2s,transform .2s;" onmouseover="this.style.borderColor='rgba(245,158,11,.4)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='#334155';this.style.transform='translateY(0)'">
        <div style="position:absolute;top:1rem;right:1rem;font-size:1.5rem;opacity:.15;">🏫</div>
        <div style="font-family:'DM Mono',monospace;font-size:.62rem;color:#64748b;text-transform:uppercase;letter-spacing:.1em;margin-bottom:.5rem;">Classes</div>
        <div style="font-family:'Instrument Serif',serif;font-size:2.2rem;font-weight:400;color:#f1f5f9;line-height:1;" x-text="kpi.classes">18</div>
        <div style="display:flex;align-items:center;gap:.35rem;margin-top:.6rem;">
            <span style="font-size:.7rem;color:#f59e0b;background:rgba(245,158,11,.1);padding:.1rem .45rem;border-radius:100px;border:1px solid rgba(245,158,11,.2);">→ 0</span>
            <span style="font-size:.7rem;color:#64748b;">inchangé</span>
        </div>
    </div>

    {{-- Parents --}}
    <div class="kpi-card" data-animate="card" style="opacity:0;background:#1e293b;border:1px solid #334155;border-radius:.875rem;padding:1.25rem;position:relative;overflow:hidden;transition:border-color .2s,transform .2s;" onmouseover="this.style.borderColor='rgba(139,92,246,.4)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='#334155';this.style.transform='translateY(0)'">
        <div style="position:absolute;top:1rem;right:1rem;font-size:1.5rem;opacity:.15;">👨‍👩‍👧</div>
        <div style="font-family:'DM Mono',monospace;font-size:.62rem;color:#64748b;text-transform:uppercase;letter-spacing:.1em;margin-bottom:.5rem;">Parents / Tuteurs</div>
        <div style="font-family:'Instrument Serif',serif;font-size:2.2rem;font-weight:400;color:#f1f5f9;line-height:1;" x-text="kpi.parents">634</div>
        <div style="display:flex;align-items:center;gap:.35rem;margin-top:.6rem;">
            <span style="font-size:.7rem;color:#10b981;background:rgba(16,185,129,.1);padding:.1rem .45rem;border-radius:100px;border:1px solid rgba(16,185,129,.2);">↑ +28</span>
            <span style="font-size:.7rem;color:#64748b;">inscrits cette année</span>
        </div>
    </div>

    {{-- Taux présence --}}
    <div class="kpi-card" data-animate="card" style="opacity:0;background:#1e293b;border:1px solid #334155;border-radius:.875rem;padding:1.25rem;position:relative;overflow:hidden;transition:border-color .2s,transform .2s;" onmouseover="this.style.borderColor='rgba(16,185,129,.4)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='#334155';this.style.transform='translateY(0)'">
        <div style="position:absolute;top:1rem;right:1rem;font-size:1.5rem;opacity:.15;">✅</div>
        <div style="font-family:'DM Mono',monospace;font-size:.62rem;color:#64748b;text-transform:uppercase;letter-spacing:.1em;margin-bottom:.5rem;">Taux présence</div>
        <div style="font-family:'Instrument Serif',serif;font-size:2.2rem;font-weight:400;color:#10b981;line-height:1;" x-text="kpi.attendance + '%'">94%</div>
        <div style="display:flex;align-items:center;gap:.35rem;margin-top:.6rem;">
            <span style="font-size:.7rem;color:#10b981;background:rgba(16,185,129,.1);padding:.1rem .45rem;border-radius:100px;border:1px solid rgba(16,185,129,.2);">↑ +2%</span>
            <span style="font-size:.7rem;color:#64748b;">cette semaine</span>
        </div>
    </div>

    {{-- Paiements --}}
    <div class="kpi-card" data-animate="card" style="opacity:0;background:#1e293b;border:1px solid #334155;border-radius:.875rem;padding:1.25rem;position:relative;overflow:hidden;transition:border-color .2s,transform .2s;" onmouseover="this.style.borderColor='rgba(244,63,94,.4)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='#334155';this.style.transform='translateY(0)'">
        <div style="position:absolute;top:1rem;right:1rem;font-size:1.5rem;opacity:.15;">💳</div>
        <div style="font-family:'DM Mono',monospace;font-size:.62rem;color:#64748b;text-transform:uppercase;letter-spacing:.1em;margin-bottom:.5rem;">Paiements en attente</div>
        <div style="font-family:'Instrument Serif',serif;font-size:2.2rem;font-weight:400;color:#f43f5e;line-height:1;" x-text="kpi.pending_payments">47</div>
        <div style="display:flex;align-items:center;gap:.35rem;margin-top:.6rem;">
            <span style="font-size:.7rem;color:#f43f5e;background:rgba(244,63,94,.1);padding:.1rem .45rem;border-radius:100px;border:1px solid rgba(244,63,94,.2);">⚠ Urgent</span>
            <span style="font-size:.7rem;color:#64748b;">à régulariser</span>
        </div>
    </div>

</div>

{{-- ── Charts + Activité ── --}}
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1rem;margin-bottom:1.75rem;">

    {{-- Graphique présences --}}
    <div style="background:#1e293b;border:1px solid #334155;border-radius:.875rem;padding:1.25rem;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <div>
                <div style="font-size:.88rem;font-weight:700;">Présences — 7 derniers jours</div>
                <div style="font-family:'DM Mono',monospace;font-size:.62rem;color:#64748b;">Présents / Absents / Retards</div>
            </div>
            <select style="font-family:'DM Mono',monospace;font-size:.65rem;background:#27374d;border:1px solid #334155;color:#94a3b8;padding:.3rem .5rem;border-radius:.4rem;cursor:pointer;">
                <option>7 jours</option>
                <option>30 jours</option>
                <option>Semestre</option>
            </select>
        </div>
        {{-- Bars --}}
        <div style="display:flex;align-items:flex-end;gap:.5rem;height:120px;padding-bottom:.5rem;" id="chart-bars">
            @foreach([['L','92','4','4'],['M','88','8','4'],['Me','95','3','2'],['J','90','6','4'],['V','87','9','4'],['S','82','12','6'],['D','—','—','—']] as $d)
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:.2rem;">
                @if($d[1] !== '—')
                <div style="width:100%;display:flex;flex-direction:column;gap:1px;border-radius:3px 3px 0 0;overflow:hidden;height:{{ (int)$d[1] }}px;">
                    <div style="flex:1;background:rgba(99,102,241,.7);border-radius:3px 3px 0 0;"></div>
                </div>
                @else
                <div style="flex:1;"></div>
                @endif
                <div style="font-family:'DM Mono',monospace;font-size:.55rem;color:#64748b;">{{ $d[0] }}</div>
            </div>
            @endforeach
        </div>
        <div style="display:flex;gap:1rem;margin-top:.75rem;">
            <div style="display:flex;align-items:center;gap:.35rem;font-size:.68rem;color:#94a3b8;"><span style="width:8px;height:8px;background:rgba(99,102,241,.7);border-radius:2px;"></span>Présents</div>
            <div style="display:flex;align-items:center;gap:.35rem;font-size:.68rem;color:#94a3b8;"><span style="width:8px;height:8px;background:rgba(244,63,94,.7);border-radius:2px;"></span>Absents</div>
            <div style="display:flex;align-items:center;gap:.35rem;font-size:.68rem;color:#94a3b8;"><span style="width:8px;height:8px;background:rgba(245,158,11,.7);border-radius:2px;"></span>Retards</div>
        </div>
    </div>

    {{-- Répartition par niveau --}}
    <div style="background:#1e293b;border:1px solid #334155;border-radius:.875rem;padding:1.25rem;">
        <div style="font-size:.88rem;font-weight:700;margin-bottom:.3rem;">Répartition niveaux</div>
        <div style="font-family:'DM Mono',monospace;font-size:.62rem;color:#64748b;margin-bottom:1rem;">Effectifs par cycle</div>

        @foreach([['Primaire','312','37','rgba(16,185,129,.7)'],['Secondaire','428','51','rgba(99,102,241,.7)'],['Supérieur','107','12','rgba(245,158,11,.7)']] as $n)
        <div style="margin-bottom:.875rem;">
            <div style="display:flex;justify-content:space-between;margin-bottom:.3rem;">
                <span style="font-size:.78rem;font-weight:500;">{{ $n[0] }}</span>
                <span style="font-family:'DM Mono',monospace;font-size:.65rem;color:#94a3b8;">{{ $n[1] }} — {{ $n[2] }}%</span>
            </div>
            <div style="height:6px;background:#27374d;border-radius:100px;overflow:hidden;">
                <div style="height:100%;width:{{ $n[2] }}%;background:{{ $n[3] }};border-radius:100px;transition:width 1s ease;" class="bar-fill"></div>
            </div>
        </div>
        @endforeach

        <div style="margin-top:1rem;padding-top:.875rem;border-top:1px solid #334155;">
            <div style="font-family:'DM Mono',monospace;font-size:.6rem;color:#64748b;margin-bottom:.5rem;">TOTAL</div>
            <div style="font-family:'Instrument Serif',serif;font-size:1.75rem;color:#f1f5f9;">847 <span style="font-size:.8rem;color:#64748b;font-family:'Syne',sans-serif;">apprenants</span></div>
        </div>
    </div>

</div>

{{-- ── Classes récentes + Activité récente ── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.75rem;">

    {{-- Classes --}}
    <div style="background:#1e293b;border:1px solid #334155;border-radius:.875rem;overflow:hidden;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid #334155;display:flex;align-items:center;justify-content:space-between;">
            <div style="font-size:.88rem;font-weight:700;">Classes actives</div>
            <a href="#" style="font-family:'DM Mono',monospace;font-size:.65rem;color:#6366f1;text-decoration:none;">Voir tout →</a>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#27374d;">
                        <th style="font-family:'DM Mono',monospace;font-size:.6rem;color:#64748b;text-transform:uppercase;letter-spacing:.08em;padding:.6rem 1.25rem;text-align:left;">Classe</th>
                        <th style="font-family:'DM Mono',monospace;font-size:.6rem;color:#64748b;text-transform:uppercase;letter-spacing:.08em;padding:.6rem .75rem;text-align:center;">Effectif</th>
                        <th style="font-family:'DM Mono',monospace;font-size:.6rem;color:#64748b;text-transform:uppercase;letter-spacing:.08em;padding:.6rem .75rem;text-align:center;">Présence</th>
                        <th style="font-family:'DM Mono',monospace;font-size:.6rem;color:#64748b;text-transform:uppercase;letter-spacing:.08em;padding:.6rem 1.25rem;text-align:center;">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([
                        ['Terminale C','38','96%','Actif','teal'],
                        ['3ème 1','42','91%','Actif','teal'],
                        ['6ème A','45','88%','Actif','teal'],
                        ['Tle BTP 2','35','94%','Actif','teal'],
                        ['2nde 3','40','85%','Actif','teal'],
                    ] as $c)
                    <tr style="border-bottom:1px solid #334155;transition:background .15s;" onmouseover="this.style.background='rgba(255,255,255,.02)'" onmouseout="this.style.background='transparent'">
                        <td style="padding:.7rem 1.25rem;font-size:.8rem;font-weight:600;">{{ $c[0] }}</td>
                        <td style="padding:.7rem .75rem;text-align:center;font-family:'DM Mono',monospace;font-size:.72rem;color:#94a3b8;">{{ $c[1] }}</td>
                        <td style="padding:.7rem .75rem;text-align:center;">
                            <span style="font-family:'DM Mono',monospace;font-size:.7rem;color:#10b981;">{{ $c[2] }}</span>
                        </td>
                        <td style="padding:.7rem 1.25rem;text-align:center;">
                            <span style="font-family:'DM Mono',monospace;font-size:.62rem;padding:.15rem .5rem;border-radius:100px;background:rgba(16,185,129,.1);color:#10b981;border:1px solid rgba(16,185,129,.2);">{{ $c[3] }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:.75rem 1.25rem;border-top:1px solid #334155;display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:.72rem;color:#64748b;">Affichage 1–5 sur 18</span>
            <div style="display:flex;gap:.4rem;">
                <button style="padding:.25rem .6rem;background:#27374d;border:1px solid #334155;border-radius:.35rem;color:#94a3b8;font-size:.7rem;cursor:pointer;">‹ Préc</button>
                <button style="padding:.25rem .6rem;background:#27374d;border:1px solid #334155;border-radius:.35rem;color:#94a3b8;font-size:.7rem;cursor:pointer;">Suiv ›</button>
            </div>
        </div>
    </div>

    {{-- Activité récente --}}
    <div style="background:#1e293b;border:1px solid #334155;border-radius:.875rem;overflow:hidden;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid #334155;display:flex;align-items:center;justify-content:space-between;">
            <div style="font-size:.88rem;font-weight:700;">Activité récente</div>
            <span style="font-family:'DM Mono',monospace;font-size:.62rem;padding:.15rem .5rem;background:rgba(16,185,129,.1);color:#10b981;border:1px solid rgba(16,185,129,.2);border-radius:100px;">En direct</span>
        </div>
        <div style="padding:.5rem 0;">
            @foreach([
                ['📝','Notes saisies — Maths 3ème 1','Prof. Amoussou','5 min','indigo'],
                ['👤','Nouvel apprenant inscrit','Kofi Danso','12 min','green'],
                ['💳','Paiement reçu — 25 000 FCFA','Famille Mensah','1h','green'],
                ['🔔','Absence signalée — Terminale C','Auto','2h','gold'],
                ['📄','Bulletin généré — Tle C','Directeur','3h','indigo'],
                ['👩‍🏫','Enseignant affilié — Prof. Bossou','Directeur','5h','green'],
                ['⚠️','Note modifiée avec justification','Prof. Koudé','7h','coral'],
            ] as $a)
            <div style="display:flex;align-items:flex-start;gap:.75rem;padding:.65rem 1.25rem;border-bottom:1px solid rgba(51,65,85,.5);transition:background .15s;" onmouseover="this.style.background='rgba(255,255,255,.02)'" onmouseout="this.style.background='transparent'">
                <div style="width:30px;height:30px;border-radius:50%;background:#27374d;display:flex;align-items:center;justify-content:center;font-size:.8rem;flex-shrink:0;margin-top:.1rem;">{{ $a[0] }}</div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:.78rem;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $a[1] }}</div>
                    <div style="font-family:'DM Mono',monospace;font-size:.6rem;color:#64748b;">{{ $a[2] }}</div>
                </div>
                <div style="font-family:'DM Mono',monospace;font-size:.6rem;color:#64748b;flex-shrink:0;">{{ $a[3] }}</div>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- ── Enseignants + Paiements en attente ── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">

    {{-- Top enseignants --}}
    <div style="background:#1e293b;border:1px solid #334155;border-radius:.875rem;overflow:hidden;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid #334155;display:flex;align-items:center;justify-content:space-between;">
            <div style="font-size:.88rem;font-weight:700;">Enseignants actifs</div>
            <a href="#" style="font-family:'DM Mono',monospace;font-size:.65rem;color:#6366f1;text-decoration:none;">Gérer →</a>
        </div>
        <div style="padding:.5rem 0;">
            @foreach([
                ['Sylvie Amoussou','Maths — Tle C, 3ème 1','SA','actif'],
                ['Fabrice Bossou','Physique — 3ème, 2nde','FB','actif'],
                ['Jean Koudé','Histoire — 6ème, 5ème','JK','actif'],
                ['Marie Adjovi','Français — Tle, 1ère','MA','actif'],
                ['Kofi Mensah','SVT — 2nde, 3ème','KM','en attente'],
            ] as $t)
            <div style="display:flex;align-items:center;gap:.75rem;padding:.65rem 1.25rem;border-bottom:1px solid rgba(51,65,85,.5);transition:background .15s;" onmouseover="this.style.background='rgba(255,255,255,.02)'" onmouseout="this.style.background='transparent'">
                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#10b981);display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:800;color:white;flex-shrink:0;">{{ $t[2] }}</div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:.8rem;font-weight:600;">{{ $t[0] }}</div>
                    <div style="font-family:'DM Mono',monospace;font-size:.6rem;color:#64748b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $t[1] }}</div>
                </div>
                <span style="font-family:'DM Mono',monospace;font-size:.62rem;padding:.12rem .45rem;border-radius:100px;
                    {{ $t[3] === 'actif' ? 'background:rgba(16,185,129,.1);color:#10b981;border:1px solid rgba(16,185,129,.2)' : 'background:rgba(245,158,11,.1);color:#f59e0b;border:1px solid rgba(245,158,11,.2)' }}">
                    {{ $t[3] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Paiements en attente --}}
    <div style="background:#1e293b;border:1px solid #334155;border-radius:.875rem;overflow:hidden;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid #334155;display:flex;align-items:center;justify-content:space-between;">
            <div style="font-size:.88rem;font-weight:700;">Paiements en attente</div>
            <span style="font-family:'DM Mono',monospace;font-size:.62rem;padding:.15rem .5rem;background:rgba(244,63,94,.1);color:#f43f5e;border:1px solid rgba(244,63,94,.2);border-radius:100px;">47 urgents</span>
        </div>
        <div style="padding:.5rem 0;">
            @foreach([
                ['Aminata Mensah','Terminale C','Scolarité S1','50 000','en attente'],
                ['Kofi Danso','3ème 1','Inscription','25 000','partiel'],
                ['Fatoumata Bah','6ème A','Scolarité S1','45 000','en attente'],
                ['Jean-Marc Glo','Tle BTP 2','Scolarité S1','55 000','en attente'],
                ['Abigaël Yovo','2nde 3','Cantine','15 000','partiel'],
            ] as $p)
            <div style="display:flex;align-items:center;gap:.75rem;padding:.65rem 1.25rem;border-bottom:1px solid rgba(51,65,85,.5);transition:background .15s;" onmouseover="this.style.background='rgba(255,255,255,.02)'" onmouseout="this.style.background='transparent'">
                <div style="flex:1;min-width:0;">
                    <div style="font-size:.78rem;font-weight:600;">{{ $p[0] }}</div>
                    <div style="font-family:'DM Mono',monospace;font-size:.6rem;color:#64748b;">{{ $p[1] }} — {{ $p[2] }}</div>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    <div style="font-family:'DM Mono',monospace;font-size:.72rem;color:#f1f5f9;margin-bottom:.1rem;">{{ $p[3] }} F</div>
                    <span style="font-family:'DM Mono',monospace;font-size:.58rem;padding:.1rem .4rem;border-radius:100px;
                        {{ $p[4] === 'en attente' ? 'background:rgba(244,63,94,.1);color:#f43f5e;border:1px solid rgba(244,63,94,.2)' : 'background:rgba(245,158,11,.1);color:#f59e0b;border:1px solid rgba(245,158,11,.2)' }}">
                        {{ $p[4] }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        <div style="padding:.75rem 1.25rem;border-top:1px solid #334155;">
            <button style="width:100%;padding:.5rem;background:rgba(244,63,94,.08);border:1px solid rgba(244,63,94,.2);border-radius:.5rem;color:#f43f5e;font-size:.78rem;font-weight:600;cursor:pointer;transition:all .2s;" onmouseover="this.style.background='rgba(244,63,94,.15)'" onmouseout="this.style.background='rgba(244,63,94,.08)'">
                Gérer tous les paiements en attente →
            </button>
        </div>
    </div>

</div>

</div>

<script>
function dashboard() {
    return {
        kpi: {
            students: 847,
            teachers: 42,
            classes: 18,
            parents: 634,
            attendance: 94,
            pending_payments: 47,
        },
        init() {
            // Animer les KPI cards
            if (window.Motion) {
                const cards = document.querySelectorAll('.kpi-card');
                window.Motion.animate(cards,
                    { opacity: [0,1], y: [16,0] },
                    { delay: window.Motion.stagger(.08), duration: .4, easing: 'ease-out' }
                );

                // Animer les barres de progression
                setTimeout(() => {
                    document.querySelectorAll('.bar-fill').forEach(b => {
                        b.style.transition = 'width 1s ease';
                    });
                }, 300);
            }
        }
    }
}
</script>
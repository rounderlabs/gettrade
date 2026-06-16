<template>
    <section class="team-widget-section py-2">
        <div class="custom-container">
            <div class="title mb-3 d-flex justify-content-between align-items-center">
                <h2 class="text-white mb-0">My Team Stats</h2>
            </div>
            
            <div class="row g-3">
                <!-- Total Team -->
                <div class="col-6 col-sm-3">
                    <div class="glass-team-box">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="team-icon bg-primary-glow">
                                <vue-feather type="users" size="14" />
                            </div>
                            <span class="team-label text-uppercase">Total Team</span>
                        </div>
                        <h2 class="team-value text-white mb-0">{{ team.total }}</h2>
                    </div>
                </div>

                <!-- Total Directs -->
                <div class="col-6 col-sm-3">
                    <div class="glass-team-box">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="team-icon bg-success-glow">
                                <vue-feather type="user-check" size="14" />
                            </div>
                            <span class="team-label text-uppercase">Total Directs</span>
                        </div>
                        <h2 class="team-value text-white mb-0">{{ team.direct }}</h2>
                    </div>
                </div>

                <!-- Active Team -->
                <div class="col-6 col-sm-3">
                    <div class="glass-team-box">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="team-icon bg-purple-glow">
                                <vue-feather type="activity" size="14" />
                            </div>
                            <span class="team-label text-uppercase">Active Team</span>
                        </div>
                        <h2 class="team-value text-white mb-0">{{ team.active_total }}</h2>
                    </div>
                </div>

                <!-- Active Directs -->
                <div class="col-6 col-sm-3">
                    <div class="glass-team-box">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="team-icon bg-amber-glow">
                                <vue-feather type="user-plus" size="14" />
                            </div>
                            <span class="team-label text-uppercase">Active Directs</span>
                        </div>
                        <h2 class="team-value text-white mb-0">{{ team.active_direct }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import {Link} from "@inertiajs/vue3";
import VueFeather from "vue-feather";

export default {
    name: "TeamWidget",
    components: {Link, VueFeather},
    props: {
        team: Object
    }
};
</script>

<style scoped>
.glass-team-box {
    background: rgba(30, 41, 59, 0.45);
    backdrop-filter: blur(12px) saturate(160%);
    -webkit-backdrop-filter: blur(12px) saturate(160%);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    padding: 18px;
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
}

.glass-team-box:hover {
    transform: translateY(-4px);
    border-color: rgba(255, 255, 255, 0.15);
    box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.45);
}

.team-icon {
    width: 26px;
    height: 26px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-primary-glow { background: rgba(56, 189, 248, 0.12); border: 1px solid rgba(56, 189, 248, 0.2); color: #38bdf8; }
.bg-success-glow { background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; }
.bg-purple-glow { background: rgba(168, 85, 247, 0.12); border: 1px solid rgba(168, 85, 247, 0.2); color: #a855f7; }
.bg-amber-glow { background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.2); color: #f59e0b; }

.team-label {
    font-size: 0.68rem;
    color: #64748b;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.team-value {
    font-size: 1.5rem;
    font-weight: 800;
    margin-top: 4px;
}
</style>

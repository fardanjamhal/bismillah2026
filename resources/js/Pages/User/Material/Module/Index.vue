<template>
  <Head>
    <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Modul / Materi</title>
  </Head>

  <!--start page wrapper -->
  <div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
       <div class="breadcrumb-title pe-3 d-flex align-items-center gap-2">
        <i class="bx bx-library text-blue-gradient"></i>
        <span>Modul / Materi</span>
      </div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item">
                <a href="javascript:;" class="crumb-link">
                  <i class="bx bx-book-open text-blue-gradient"></i>
                </a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Data Modul / Materi</li>
            </ol>
          </nav>
        </div>
      </div>
      <!--end breadcrumb-->

      <hr class="soft" />

      <!-- Kategori -->
      <div v-if="categoryModules.length" class="category-list">
        <div class="card cat-card mb-4" v-for="category in categoryModules" :key="category.id">
          <!-- Header kategori -->
          <div class="card-header cat-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
              <div class="cat-icon">
                <i class="bx bx-book-content"></i>
              </div>
              <div>
                <h6 class="text-white mb-0 d-flex align-items-center gap-2">
                  <i class="bx bx-category-alt"></i>
                  <span>Modul / Materi {{ category.name }}</span>
                </h6>
              </div>
            </div>
          </div>

          <!-- Body kategori -->
          <transition name="fade" mode="out-in">
            <div v-show="collapseStatus[category.id]" :id="'collapse'+category.id" class="collapse show">
              <div class="card-body">
                <div class="row g-3 g-md-4">
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4" v-for="materialModule in category.module" :key="materialModule.id">
                    <div class="card module-card overflow-hidden border-0 h-100">
                      <!-- Chip Enrolled: baris terpisah agar tak menutupi judul -->

                      <!-- Header modul -->
                        <div class="card-header module-head">
                        <div class="d-flex align-items-center gap-3">
                          <div class="module-icon d-flex align-items-center justify-content-center">
                            <i class="bx bx-book"></i>
                          </div>
                          <div class="flex-grow-1 pe-2 d-flex align-items-center">
                            <h6 class="mb-0 module-title text-truncate" :title="materialModule.title">
                              {{ materialModule.title }}
                            </h6>
                          </div>
                        </div>
                      </div>


                      <!-- Body modul -->
                      <div class="card-body">
                        <div class="desc-wrap">
                          <p class="mb-0 text-secondary font-14 clamp-3">{{ materialModule.description }}</p>
                        </div>
                      </div>

                      <!-- Footer modul -->
                      <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="text-end d-flex align-items-center gap-2">
                            <Link v-if="materialModule.status == 'active'" :href="`/user/modules/${materialModule.id}`" class="btn-modern">
                              <i class="bx bx-show-alt me-1"></i> Lihat Detail
                            </Link>
                            
                            <span v-else class="status-chip" :class="{ 'inactive': materialModule.status == 'inactive', 'progress': materialModule.status === 'inprogress'}">
                              <i :class="{ 'bx bx-block': materialModule.status === 'inactive', 'bx bx-time-five': materialModule.status === 'inprogress' }"></i>
                              {{ materialModule.status === 'inactive' ? 'Inactive' : 'In Progress' }}
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Jika kategori tanpa modul -->
                  <div v-if="!category.module || !category.module.length" class="col-12">
                    <div class="card empty-card-sm text-center">
                      <div class="card-body py-4">
                        <div class="empty-icon-sm"><i class="bx bx-book-open"></i></div>
                        <p class="mb-0 text-muted">Belum ada materi pada kategori ini.</p>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </transition>
        </div>
      </div>

      <!-- Empty state global -->
      <div v-else>
        <div class="row">
          <div class="col">
            <div class="card empty-card text-center">
              <div class="card-body py-5">
                <div class="empty-icon"><i class="bx bx-collection"></i></div>
                <h5 class="mb-2">Data Modul / Materi Belum Tersedia</h5>
                <p class="text-muted mb-0">Silakan kembali lagi nanti.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!--end row-->
    </div>
  </div>
  <!--end page wrapper -->
</template>

<script>
import LayoutUser from '../../../../Layouts/Layout.vue'
import { Link, Head } from '@inertiajs/inertia-vue3'
import { ref, onMounted } from 'vue'

export default {
  layout: LayoutUser,
  components: { Link, Head },
  props: {
    categoryModules: { type: Array, default: () => [] },
    userMemberCategories: { type: Array, default: () => [] }
  },
  setup(props) {
    const collapseStatus = ref({})

    onMounted(() => {
      props.categoryModules.forEach(category => {
        collapseStatus.value[category.id] = true // Open by default
      })
    })

    const toggleCollapse = (categoryId) => {
      collapseStatus.value[categoryId] = !collapseStatus.value[categoryId]
    }

    const checkMemberCategories = (categories) => {
      if (categories && categories.length > 0) {
        const categoryIds = categories.map(category => category.id)
        return props.userMemberCategories.some(entry => categoryIds.includes(entry.member_category_id))
      } else {
        return true
      }
    }

    const copyLink = async (url) => {
      try { await navigator.clipboard.writeText(url) } catch (e) { console.warn('Clipboard copy failed', e) }
    }

    const formatDate = (dateStr) => {
      try {
        const d = new Date(dateStr)
        return d.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' })
      } catch (_) { return '' }
    }

    return { collapseStatus, toggleCollapse, checkMemberCategories, copyLink, formatDate }
  }
}
</script>

<style scoped>
/* ======= Theme (Blue) ======= */
.text-blue-gradient { background: linear-gradient(135deg, #38bdf8, #1d4ed8); -webkit-background-clip: text; background-clip: text; color: transparent; }
.soft { opacity:.25; }
.header-strip { background: linear-gradient(135deg, rgba(56,189,248,.12), rgba(29,78,216,.12)); border: 1px solid rgba(29,78,216,.18); box-shadow: 0 6px 24px rgba(29,78,216,.12); }
.legend { display: inline-flex; align-items: center; gap: .4rem; font-size: .8rem; padding: .35rem .6rem; border-radius: 999px; border: 1px solid transparent; }

/* ======= Breadcrumb modern ======= */
.breadcrumb-wrap { display:flex; align-items:center; gap:1rem; }
.breadcrumb-wrap .title { font-weight:800; letter-spacing:.2px; font-size:1.06rem; }
.breadcrumb .breadcrumb-item + .breadcrumb-item::before { content:"›"; }
.home { display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:10px; background:#f1f5f9; color:#0f172a; transition:transform .18s ease, background .18s ease; }
.home:hover { transform:translateY(-1px); background:#e2e8f0; }

/* ======= Kartu Kategori ======= */
.cat-card { border:1px solid #e6eef7; border-radius:16px; overflow:hidden; background:#fff; box-shadow:0 10px 24px rgba(2,6,23,.05); }
.cat-header { background: linear-gradient(135deg, #1e3a8a, #1d4ed8); padding:.9rem 1rem; border:none; }
.cat-icon { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:rgba(255,255,255,.12); color:#fff; font-size:1.2rem; }
.plus-pill{ display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:10px; background:rgba(255,255,255,.1); color:#fff; }
.btn-toggle{ width:34px; height:34px; border:none; border-radius:10px; display:inline-flex; align-items:center; justify-content:center; background:#ffffff1a; color:#fff; transition:transform .15s ease, background .15s ease; }
.btn-toggle:hover{ transform:translateY(-1px); background:#ffffff26; }

/* ======= Kartu Modul ======= */
.module-card{ background:#fff; border:1px solid #eef2f7; border-radius:16px; box-shadow:0 10px 24px rgba(2,6,23,.05); transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease; position:relative; }
.module-card:hover{ transform:translateY(-3px); box-shadow:0 16px 36px rgba(2,6,23,.1); border-color:#dbe3f0; }

/* chip enrolled baris sendiri */
.chip-enrolled{ display:inline-flex; align-items:center; gap:.35rem; background: linear-gradient(135deg, #bfdbfe, #60a5fa); color:#0f172a; font-weight:600; padding:.28rem .55rem; border-radius:.8rem; font-size:.75rem; border:1px solid rgba(29,78,216,.45); box-shadow: 0 4px 12px rgba(59,130,246,.25); }

.module-head{ background:linear-gradient(180deg, #fff, #f9fbff); border-bottom:1px solid #eef2f7; }
.module-icon{ min-width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg, #eff6ff, #dbeafe); color:#2563eb; font-size:1.2rem; box-shadow:0 4px 10px rgba(37,99,235,.12); }
.module-title{ font-weight:700; color:#0f172a; letter-spacing:.2px; }

.desc-wrap{ min-height: 96px; }
.clamp-3{ overflow:hidden; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; }

/* Footer/status */
.status-dot{ display:inline-block; width:8px; height:8px; border-radius:999px; margin-right:.4rem; }
.dot-active{ background:#22c55e; }
.dot-warning{ background:#f59e0b; }
.dot-danger{ background:#ef4444; }

/* Buttons */
.btn-modern { display: inline-flex; align-items:center; gap:.5rem; border: 1px solid rgba(29,78,216,.35); background: linear-gradient(135deg, rgba(59,130,246,.18), rgba(29,78,216,.18)); color:#1e3a8a; padding:.5rem .8rem; border-radius:.8rem; font-weight:700; text-decoration:none; transition: all .2s ease; }
.btn-modern:hover { transform: translateY(-1px); background: linear-gradient(135deg, rgba(59,130,246,.26), rgba(29,78,216,.26)); }
.btn-ghost { border: 1px dashed rgba(30,41,59,.25); background: transparent; color:#1f2937; padding:.45rem .55rem; border-radius:.7rem; transition: all .2s ease; }
.btn-ghost:hover { background: rgba(2,6,23,.04); }

/* Status chip (non-active states) */
.status-chip { display: inline-flex; align-items:center; gap:.4rem; padding:.3rem .6rem; border-radius: .7rem; font-size:.8rem; font-weight:600; border:1px solid transparent; }
.status-chip.inactive { color:#991b1b; background: rgba(248,113,113,.15); border-color: rgba(239,68,68,.35); }
.status-chip.progress { color:#9a3412; background: rgba(251,146,60,.15); border-color: rgba(234,88,12,.35); }

/* Empty states */
.empty-card{ border:1px dashed #e5e7eb; border-radius:16px; background:linear-gradient(180deg, #fff 0%, #fafbfd 100%); box-shadow:0 8px 20px rgba(2,6,23,.04); }
.empty-icon{ width:64px; height:64px; margin:0 auto 12px; border-radius:16px; display:grid; place-items:center; background:#eef2ff; color:#4f46e5; font-size:30px; }
.empty-card-sm{ border:1px dashed #e5e7eb; border-radius:14px; background:#fff; }
.empty-icon-sm{ width:48px; height:48px; margin:0 auto 10px; border-radius:12px; display:grid; place-items:center; background:#f1f5ff; color:#4f46e5; font-size:22px; }

/* Animasi */
.fade-enter-active, .fade-leave-active { transition: opacity .35s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

/* Responsif */
@media (max-width: 576px){
  .cat-header { padding:.8rem .9rem; }
  .module-icon{ min-width:38px; height:38px; font-size:1.05rem; border-radius:10px; }
  .module-title{ font-size:1rem; }
  .btn-modern{ width:100%; }
}

/* Aksesibilitas */
@media (prefers-reduced-motion: reduce){
  .btn-modern, .module-card, .module-card:hover, .btn-toggle{ transition:none; transform:none; box-shadow:none; }
}
</style>

<template>
  <Head>
    <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Video Pembelajaran</title>
  </Head>
  <!--start page wrapper -->
  <div class="page-wrapper">
    <div class="page-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3 d-flex align-items-center gap-2">
          <i class="bx bx-slideshow me-1 text-blue-gradient"></i>
          <span>Video Pembelajaran</span>
        </div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item">
                <a href="javascript:;" class="crumb-link">
                  <i class="bx bx-home-alt"></i>
                </a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Data Video Pembelajaran</li>
            </ol>
          </nav>
        </div>
      </div>
      <!--end breadcrumb-->

      <hr class="soft" />

      <div v-if="!categoryVideoModules.length">
        <div class="row">
          <div class="col">
            <div class="empty-state rounded-4 p-5 text-center">
              <div class="icon-wrap mx-auto mb-3">
                <i class="bx bx-video-off"></i>
              </div>
              <h5 class="mb-1">Data Video Pembelajaran Belum Tersedia</h5>
              <p class="mb-0 text-muted">Saat data tersedia, modul video akan tampil rapi di sini.</p>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="category-list">
        <div class="card elevated-card mb-4" v-for="category in categoryVideoModules" :key="category.id">
          <div class="card-header category-header d-flex justify-content-between align-items-center">
            <h6 class="text-white mb-0 d-flex align-items-center gap-2">
              <i class="bx bx-category-alt" style="padding: 0.8rem;"></i>
              <span>Video Pembelajaran {{ category.name }}</span>
            </h6>
          </div>

          <transition name="fade" mode="out-in">
            <div v-show="collapseStatus[category.id]" :id="'collapse'+category.id" class="collapse show">
              <div class="card-body">
                <div class="row g-3 g-md-4">
                  <div class="col-12 col-sm-6 col-lg-4" v-for="videoModule in category.video_module" :key="videoModule.id">
                    <div class="card video-card h-100">

                      <div class="card-header video-head d-flex align-items-center">
                        <div class="thumb flex-shrink-0 me-2">
                          <i class="bx bx-video-recording"></i>
                        </div>
                        <div class="flex-grow-1 pe-2">
                          <h6 class="title mb-0 text-truncate" :title="videoModule.title">{{ videoModule.title }}</h6>
                        </div>
                      </div>

                      <div class="card-body">
                        <p class="mb-0 description" v-text="videoModule.description"></p>
                      </div>

                      <div class="card-footer d-flex justify-content-between align-items-center">
                        <div class="status-wrap" v-if="videoModule.status !== 'active'">
                          <span class="status-chip" :class="{
                            'inactive': videoModule.status === 'inactive',
                            'progress': videoModule.status === 'inprogress'
                          }">
                            <i :class="{
                              'bx bx-block': videoModule.status === 'inactive',
                              'bx bx-time-five': videoModule.status === 'inprogress'
                            }"></i>
                            {{ videoModule.status === 'inactive' ? 'Inactive' : 'In Progress' }}
                          </span>
                        </div>
                        <Link v-else :href="`/user/video-modules/${videoModule.id}`" class="btn-modern">
                          <i class="bx bx-play-circle me-1"></i>
                          Lihat Detail Video
                        </Link>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </transition>
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
    categoryVideoModules: { type: Array, default: () => [] },
    userMemberCategories: { type: Array, default: () => [] }
  },
  setup(props) {
    const collapseStatus = ref({})

    onMounted(() => {
      props.categoryVideoModules.forEach(category => {
        collapseStatus.value[category.id] = true // Open by default
      })
    })

    const toggleCollapse = (categoryId) => {
      collapseStatus.value[categoryId] = !collapseStatus[categoryId]
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
/* ====== Blue Theme Helpers ====== */
.text-blue-gradient { background: linear-gradient(135deg, #38bdf8, #1d4ed8); -webkit-background-clip: text; background-clip: text; color: transparent; }
.soft { opacity: .25; }

.header-strip {
  background: linear-gradient(135deg, rgba(56,189,248,.12), rgba(29,78,216,.12));
  border: 1px solid rgba(29,78,216,.18);
  box-shadow: 0 6px 24px rgba(29,78,216,.12);
}
.legend { display: inline-flex; align-items: center; gap: .4rem; font-size: .8rem; padding: .35rem .6rem; border-radius: 999px; border: 1px solid transparent; }

/* ====== Category Card ====== */
.elevated-card { border: none; overflow: hidden; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,.08); }
.category-header { background: linear-gradient(135deg, #1e3a8a, #1d4ed8); border: none; padding: .9rem 1.2rem; }
.btn-toggle { background: rgba(255,255,255,.2); border: none; color: #fff; width: 40px; height: 40px; border-radius: 12px; display:flex; align-items:center; justify-content:center; transition: all .25s ease; }
.btn-toggle:hover { background: rgba(255,255,255,.32); transform: translateY(-1px); }

/* ====== Video Card ====== */
.video-card { position: relative; border: 1px solid rgba(2,6,23,.06); border-radius: 1rem; transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease; background: linear-gradient(180deg, rgba(255,255,255,.92), rgba(248,250,252,.92)); backdrop-filter: blur(6px); }
.video-card:hover { transform: translateY(-2px); box-shadow: 0 12px 26px rgba(2,6,23,.12); border-color: rgba(29,78,216,.25); }

.video-head { background: linear-gradient(135deg, rgba(37,99,235,.08), rgba(29,78,216,.06)); border: none; }
.video-head .thumb { width: 42px; height: 42px; border-radius: 12px; display: grid; place-items: center; background: rgba(37,99,235,.14); color: #1d4ed8; }
.video-head .title { font-weight: 700; line-height: 1.25; }
.video-head .muted { color: #64748b; }

.description { min-height: 96px; color: #475569; }

/* Buttons */
.btn-modern { display: inline-flex; align-items:center; gap:.5rem; border: 1px solid rgba(29,78,216,.35); background: linear-gradient(135deg, rgba(59,130,246,.18), rgba(29,78,216,.18)); color:#1e3a8a; padding:.5rem .8rem; border-radius:.8rem; font-weight:600; text-decoration:none; transition: all .2s ease; }
.btn-modern:hover { transform: translateY(-1px); background: linear-gradient(135deg, rgba(59,130,246,.26), rgba(29,78,216,.26)); }
.btn-ghost { border: 1px dashed rgba(30,41,59,.25); background: transparent; color:#1f2937; padding:.45rem .55rem; border-radius:.7rem; transition: all .2s ease; }
.btn-ghost:hover { background: rgba(2,6,23,.04); }

/* Status chip */
.status-chip { display: inline-flex; align-items:center; gap:.4rem; padding:.3rem .6rem; border-radius: .7rem; font-size:.8rem; font-weight:600; border:1px solid transparent; }
.status-chip.inactive { color:#991b1b; background: rgba(248,113,113,.15); border-color: rgba(239,68,68,.35); }
.status-chip.progress { color:#9a3412; background: rgba(251,146,60,.15); border-color: rgba(234,88,12,.35); }

/* Empty state */
.empty-state { border: 1px dashed rgba(2,6,23,.18); background: linear-gradient(180deg, rgba(2,6,23,.02), rgba(2,6,23,.04)); }
.icon-wrap { width: 72px; height: 72px; border-radius: 1rem; display:grid; place-items:center; background: rgba(2,6,23,.06); font-size: 32px; color:#1d4ed8; }

/* Accessibility / Motion */
.fade-enter-active, .fade-leave-active { transition: opacity 0.25s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

/* Breadcrumb link hover */
.crumb-link { color: inherit; text-decoration: none; border-radius: .6rem; padding: .25rem; }
.crumb-link:hover { background: rgba(2,6,23,.06); }
</style>
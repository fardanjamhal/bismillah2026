<template>
  <Head>
    <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Modul / Materi</title>
  </Head>

  <!--start page wrapper -->
  <div class="page-wrapper">
    <div class="page-content">
      <!--breadcrumb-->
      <div class="breadcrumb-wrap d-none d-sm-flex align-items-center mb-3">
        <div class="left">
          <span class="title">Modul / Materi</span>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item">
                <a href="javascript:;" class="home"><i class="bx bx-home-alt"></i></a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Data Modul / Materi</li>
            </ol>
          </nav>
        </div>
      </div>
      <!--end breadcrumb-->

      <hr class="divider" />

      <!-- Kategori -->
      <div
        v-if="categoryModules.length"
        class="card cat-card mb-3"
        v-for="category in categoryModules"
        :key="category.id"
      >
        <!-- Header kategori -->
        <div class="card-header cat-header d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center gap-3">
            <div class="cat-icon">
              <i class="bx bx-book-content"></i>
            </div>
            <div>
              <h6 class="text-white mb-0">
                Modul / Materi {{ category.name }}
              </h6>
              <small class="text-white-50">
                <i class="bx bx-folder-open me-1"></i>{{ (category.module && category.module.length) || 0 }} materi
              </small>
            </div>
          </div>

          <div class="d-flex align-items-center gap-2">
            <!-- ikon dekoratif “plus” (bukan aksi, hanya pemanis visual) -->
            <span class="plus-pill">
              <i class="bx bx-plus"></i>
            </span>
            <button
              type="button"
              class="btn btn-toggle"
              @click="toggleCollapse(category.id)"
              :aria-expanded="collapseStatus[category.id]"
              :aria-controls="'collapse'+category.id"
            >
              <i :class="collapseStatus[category.id] ? 'bx bx-chevron-up' : 'bx bx-chevron-down'"></i>
            </button>
          </div>
        </div>

        <!-- Body kategori -->
        <transition name="fade" mode="out-in">
          <div
            v-show="collapseStatus[category.id]"
            :id="'collapse'+category.id"
            class="collapse show"
          >
            <div class="card-body">
              <div class="row g-3 g-md-4">
                <div
                  class="col-xs-12 col-sm-12 col-md-6 col-lg-4"
                  v-for="materialModule in category.module"
                  :key="materialModule.id"
                >
                  <div class="card module-card overflow-hidden border-0">
                    <!-- Badge akses/enrolled (tetap sesuai logic asli) -->
                    <div v-if="$page.props.auth.user.member_type == 2 && $page.props.setting.enable_module_material_sales == 1">
                      <tempate v-if="$page.props.setting.module_material_sales_type == 1">
                        <span v-if="(materialModule.user_access.length > 0)" class="pill-badge bg-warning text-dark">Enrolled</span>
                      </tempate>
                      <tempate v-if="$page.props.setting.module_material_sales_type == 2">
                        <span v-if="checkMemberCategories(materialModule.member_categories) == true" class="pill-badge bg-warning text-dark"></span>
                      </tempate>
                      <tempate v-if="$page.props.setting.module_material_sales_type == 3">
                        <span v-if="(materialModule.user_access.length > 0 || checkMemberCategories(materialModule.member_categories) == true)" class="pill-badge bg-warning text-dark">Enrolled</span>
                      </tempate>
                    </div>

                    <!-- Header modul -->
                    <div class="card-header module-head">
                      <div class="d-flex align-items-start gap-3">
                        <div class="module-icon">
                          <i class="bx bx-book"></i>
                        </div>
                        <div class="flex-grow-1">
                          <h6 class="mb-1 module-title">{{ materialModule.title }}</h6>
                          <small class="text-muted">
                            <i class="bx bx-time-five me-1"></i>Materi pembelajaran
                          </small>
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
                        <div>
                          <span class="status-dot" :class="{
                            'dot-active': materialModule.status === 'active',
                            'dot-warning': materialModule.status === 'inprogress',
                            'dot-danger': materialModule.status === 'inactive'
                          }"></span>
                          <small class="text-muted">Status</small>
                        </div>
                        <div class="text-end">
                          <Link
                            v-if="materialModule.status == 'active'"
                            :href="`/user/modules/${materialModule.id}`"
                            class="btn btn-primary btn-sm cta"
                          >
                            <i class="bx bx-show-alt me-1"></i> Lihat Detail
                          </Link>
                          <span
                            v-else
                            class="badge"
                            :class="{ 'bg-danger': materialModule.status == 'inactive', 'bg-warning text-dark': materialModule.status === 'inprogress'}"
                          >
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
import LayoutUser from '../../../../Layouts/Layout.vue';
import { Link, Head } from '@inertiajs/inertia-vue3';
import { ref, onMounted } from 'vue';

export default {
  layout: LayoutUser,
  components: { Link, Head },
  props: {
    categoryModules: Object,
    userMemberCategories: Object
  },
  setup(props) {
    const collapseStatus = ref({});

    onMounted(() => {
      props.categoryModules.forEach(category => {
        collapseStatus.value[category.id] = true; // Open by default
      });
    });

    const toggleCollapse = (categoryId) => {
      collapseStatus.value[categoryId] = !collapseStatus.value[categoryId];
    };

    const checkMemberCategories = (categories) => {
      if (categories.length > 0) {
        const categoryIds = categories.map(category => category.id);
        return props.userMemberCategories.some(entry => categoryIds.includes(entry.member_category_id));
      } else {
        return true;
      }
    };

    return {
      collapseStatus,
      toggleCollapse,
      checkMemberCategories
    };
  }
}
</script>

<style scoped>
/* ======= Animasi collapse ======= */
.fade-enter-active, .fade-leave-active { transition: opacity .35s ease; }
.fade-enter, .fade-leave-to { opacity: 0; }

/* ======= Breadcrumb modern ======= */
.breadcrumb-wrap { display:flex; align-items:center; justify-content:space-between; gap:1rem; }
.breadcrumb-wrap .title { font-weight:800; letter-spacing:.2px; font-size:1.06rem; }
.breadcrumb .breadcrumb-item + .breadcrumb-item::before { content:"›"; }
.home {
  display:inline-flex; align-items:center; justify-content:center;
  width:34px; height:34px; border-radius:10px; background:#f1f5f9; color:#0f172a;
  transition:transform .18s ease, background .18s ease;
}
.home:hover { transform:translateY(-1px); background:#e2e8f0; }
.divider { opacity:.2; margin:.25rem 0 1.25rem; }

/* ======= Kartu Kategori ======= */
.cat-card {
  border:1px solid #e6eef7; border-radius:16px; overflow:hidden; background:#fff;
  box-shadow:0 10px 24px rgba(2,6,23,.05);
}
.cat-header {
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  padding:.9rem 1rem;
}
.cat-icon {
  width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center;
  background:rgba(255,255,255,.12); color:#fff; font-size:1.2rem;
}
.plus-pill{
  display:inline-flex; align-items:center; justify-content:center;
  width:34px; height:34px; border-radius:10px; background:rgba(255,255,255,.1); color:#fff;
}
.btn-toggle{
  width:34px; height:34px; border:none; border-radius:10px;
  display:inline-flex; align-items:center; justify-content:center;
  background:#ffffff1a; color:#fff; transition:transform .15s ease, background .15s ease;
}
.btn-toggle:hover{ transform:translateY(-1px); background:#ffffff26; }

/* ======= Kartu Modul ======= */
.module-card{
  background:#fff; border:1px solid #eef2f7; border-radius:16px;
  box-shadow:0 10px 24px rgba(2,6,23,.05); transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
  position:relative;
}
.module-card:hover{ transform:translateY(-3px); box-shadow:0 16px 36px rgba(2,6,23,.1); border-color:#dbe3f0; }

.pill-badge{
  position:absolute; top:10px; left:10px; border-radius:999px; padding:.2rem .5rem; font-size:.72rem;
  box-shadow:0 6px 16px rgba(0,0,0,.08);
}

.module-head{
  background:linear-gradient(180deg, #fff, #f9fbff);
  border-bottom:1px solid #eef2f7;
}
.module-icon{
  min-width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center;
  background:linear-gradient(135deg, #eff6ff, #dbeafe); color:#2563eb; font-size:1.2rem;
  box-shadow:0 4px 10px rgba(37,99,235,.12);
}
.module-title{ font-weight:700; color:#0f172a; letter-spacing:.2px; }

.desc-wrap{ min-height: 96px; }
.clamp-3{
  overflow:hidden; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical;
}

/* Footer/status */
.status-dot{
  display:inline-block; width:8px; height:8px; border-radius:999px; margin-right:.4rem;
}
.dot-active{ background:#22c55e; }
.dot-warning{ background:#f59e0b; }
.dot-danger{ background:#ef4444; }

/* CTA */
.cta{
  border-radius:10px; font-weight:700; letter-spacing:.2px; padding:.45rem .85rem;
  box-shadow:0 6px 16px rgba(37,99,235,.18); transition:transform .18s ease, box-shadow .18s ease, filter .18s ease;
}
.cta:hover{ transform:translateY(-2px); filter:brightness(1.03); }

/* Empty states */
.empty-card{
  border:1px dashed #e5e7eb; border-radius:16px; background:linear-gradient(180deg, #fff 0%, #fafbfd 100%);
  box-shadow:0 8px 20px rgba(2,6,23,.04);
}
.empty-icon{
  width:64px; height:64px; margin:0 auto 12px; border-radius:16px; display:grid; place-items:center;
  background:#eef2ff; color:#4f46e5; font-size:30px;
}
.empty-card-sm{
  border:1px dashed #e5e7eb; border-radius:14px; background:#fff;
}
.empty-icon-sm{
  width:48px; height:48px; margin:0 auto 10px; border-radius:12px; display:grid; place-items:center;
  background:#f1f5ff; color:#4f46e5; font-size:22px;
}

/* Responsif */
@media (max-width: 576px){
  .cat-header { padding:.8rem .9rem; }
  .module-icon{ min-width:38px; height:38px; font-size:1.05rem; border-radius:10px; }
  .module-title{ font-size:1rem; }
  .cta{ width:100%; margin-top:.25rem; }
}

/* Aksesibilitas */
@media (prefers-reduced-motion: reduce){
  .cta, .module-card, .module-card:hover, .btn-toggle{ transition:none; transform:none; box-shadow:none; }
}
</style>

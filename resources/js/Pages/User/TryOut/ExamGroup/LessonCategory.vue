<template>
  <Head>
    <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Kategori Mata Pelajaran</title>
  </Head>

  <!--start page wrapper -->
  <div class="page-wrapper">
    <div class="page-content">

      <!-- Breadcrumb modern -->
      <div class="breadcrumb-wrap">
        <div class="left">
          <span class="title">Tryout</span>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item">
                <a href="/user/dashboard" class="home" aria-label="Dashboard">
                  <i class="bx bx-home-alt"></i>
                </a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Data Kategori Mata Pelajaran</li>
            </ol>
          </nav>
        </div>
        <!--
        <div class="right">
          <Link :href="`/user/exam-groups`" class="btn btn-primary btn-sm back-btn">
            <i class="bx bx-left-arrow-alt"></i> Kembali
          </Link>
        </div>
        -->
      </div>

      <hr class="divider" />

      <!-- Empty state -->
      <div v-if="!lessonCategories || !lessonCategories.length">
        <div class="row">
          <div class="col-12">
            <div class="card empty-card">
              <div class="card-body text-center">
                <div class="empty-icon"><i class="bx bx-book-open"></i></div>
                <h5 class="mb-1">Kategori Mata Pelajaran Belum Tersedia</h5>
                <p class="text-muted mb-0">Silakan kembali nanti atau hubungi admin untuk pembaruan data.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Grid kategori -->
      <div v-else class="row g-3 g-md-4">
        <div
          class="col-xl-6 col-lg-6 col-md-12"
          v-for="lessonCategory in lessonCategories"
          :key="lessonCategory.id || lessonCategory.category_id"
        >
          <div class="card cat-card h-100">
            <div class="row g-0 h-100">
              <!-- Thumbnail -->
              <div class="col-md-4">
                <div class="thumb">
                  <img
                    :src="`/storage/upload_files/lesson_categories/${lessonCategory.thumbnail}`"
                    :alt="`Thumbnail ${lessonCategory.name || 'Kategori'}`"
                    class="img"
                    loading="lazy"
                    onerror="this.src='/assets/images/placeholder/cover-illustration.svg'; this.style.objectFit='contain';"
                  />
                  <div class="gradient"></div>
                </div>
              </div>

              <!-- Content -->
              <div class="col-md-8">
                <div class="card-body d-flex flex-column">
                  <div class="chips">
                    <span class="chip-main">Tryout</span>
                    <span class="dot">•</span>
                    <span class="chip-muted">Kategori</span>
                  </div>

                  <h5 class="card-title clamp-1 mb-1">
                    Tryout {{ lessonCategory.name }}
                  </h5>

                  <p class="card-text text-muted clamp-2 mb-3">
                    {{ lessonCategory.description }}.
                  </p>

                  <div class="meta">
                    <div class="info">
                      <i class="bx bx-layer"></i>
                      <span class="muted">{{ lessonCategory.name }}</span>
                    </div>

                    <Link
                      :href="`/user/exam-groups/${lessonCategory.category_id}/lesson-categories/${lessonCategory.id}/exams`"
                      class="btn btn-outline-primary btn-sm cta"
                    >
                      Selengkapnya
                      <i class="bx bx-right-arrow-alt ms-1"></i>
                    </Link>
                  </div>
                </div>
              </div>
              <!-- /Content -->
            </div>
          </div>
        </div>
      </div>
      <!--end grid-->

    </div>
  </div>
  <!--end page wrapper -->
</template>

<script>
// Layout
import LayoutUser from '../../../../Layouts/Layout.vue';
// Inertia
import { Link, Head } from '@inertiajs/inertia-vue3';

export default {
  layout: LayoutUser,
  components: { Link, Head },
  props: {
    lessonCategories: { type: [Array, Object], default: () => [] }
  }
}
</script>

<style>
/* ====== Breadcrumb modern ====== */
.breadcrumb-wrap{
  display:flex; align-items:center; justify-content:space-between;
  gap:1rem; flex-wrap:wrap; margin-bottom:1rem;
}
.breadcrumb-wrap .title{
  font-weight:800; letter-spacing:.2px; font-size:1.06rem;
}
.breadcrumb-wrap .left{display:flex; align-items:center; gap:14px;}
.breadcrumb .breadcrumb-item + .breadcrumb-item::before{
  content:"›";
}
.breadcrumb-wrap .home{
  display:inline-flex; align-items:center; justify-content:center;
  width:34px; height:34px; border-radius:10px; background:#f1f5f9; color:#0f172a;
  transition:transform .18s ease, background .18s ease;
}
.breadcrumb-wrap .home:hover{ transform:translateY(-1px); background:#e2e8f0; }
.back-btn{
  font-weight:600; border-radius:12px;
  box-shadow:0 6px 16px rgba(2,6,23,.08);
  transition:transform .18s ease, box-shadow .18s ease, filter .18s ease;
}
.back-btn:hover{ transform:translateY(-1px); box-shadow:0 10px 24px rgba(2,6,23,.12); filter:brightness(1.02); }
.divider{ opacity:.2; margin:.25rem 0 1.25rem; }

/* ====== Empty state ====== */
.empty-card{
  border:1px dashed #e5e7eb; border-radius:16px;
  background:linear-gradient(180deg, #fff 0%, #fafbfd 100%);
  box-shadow:0 8px 20px rgba(2,6,23,.04);
}
.empty-icon{
  width:64px; height:64px; margin:0 auto 12px; border-radius:16px;
  display:grid; place-items:center; background:#eef2ff; color:#4f46e5; font-size:30px;
}

/* ====== Card kategori ====== */
.cat-card{
  border:1px solid #eef2f7; border-radius:18px; overflow:hidden;
  background:#fff; box-shadow:0 10px 24px rgba(2,6,23,.05);
  transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
}
.cat-card:hover{
  transform:translateY(-3px); box-shadow:0 16px 36px rgba(2,6,23,.1); border-color:#dbe3f0;
}

/* Thumbnail */
.thumb{ position:relative; min-height:100%; background:#f8fafc; }
.thumb .img{ width:100%; height:100%; object-fit:cover; display:block; }
.thumb .gradient{
  position:absolute; inset:0; pointer-events:none;
  background:linear-gradient(180deg, rgba(15,23,42,.06) 0%, rgba(15,23,42,.02) 60%, rgba(15,23,42,0) 100%);
}

/* Chips kecil */
.chips{ display:flex; align-items:center; gap:.5rem; margin-bottom:.25rem; }
.chip-main{
  display:inline-flex; align-items:center; height:22px; padding:0 .6rem;
  border-radius:999px; font-size:.7rem; font-weight:800; letter-spacing:.3px;
  background:#eef2ff; color:#4f46e5;
}
.chip-muted{ color:#6b7280; font-size:.8rem; }
.dot{ color:#9ca3af; font-size:.9rem; }

/* Meta & CTA */
.meta{
  margin-top:auto; display:flex; align-items:center; justify-content:space-between; gap:.75rem;
}
.meta .info{ display:inline-flex; align-items:center; gap:.45rem; color:#6b7280; font-size:.92rem; }
.meta .info i{ font-size:1.05rem; }
.cta{
  border-radius:12px; font-weight:800; letter-spacing:.2px; padding:.45rem .85rem;
  transition:transform .18s ease, box-shadow .18s ease, filter .18s ease;
}
.cta:hover{ transform:translateY(-2px); filter:brightness(1.03); box-shadow:0 10px 24px rgba(37,99,235,.18); }

/* Teks clamp aman untuk toolchain */
.clamp-1, .clamp-2{
  overflow:hidden; display:-webkit-box; -webkit-box-orient:vertical;
}
.clamp-1{ -webkit-line-clamp:1; line-clamp:1; }
.clamp-2{ -webkit-line-clamp:2; line-clamp:2; }

/* Responsif */
@media (max-width: 992px){ .thumb{ min-height:190px; } }
@media (max-width: 576px){ .thumb{ min-height:160px; } }
</style>

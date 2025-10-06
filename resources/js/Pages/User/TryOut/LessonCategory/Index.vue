<template>
  <Head>
    <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Kategori Peminatan</title>
  </Head>

  <!-- Wrapper -->
  <div class="page-wrapper">
    <div class="page-content">
      
      <!-- Breadcrumb -->
      <div class="breadcrumb-wrap">
        <div class="left">
          <span class="title">Latihan Soal</span>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item">
                <a href="/user/dashboard" class="home" aria-label="Dashboard">
                  <i class="bx bx-home-alt"></i>
                </a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Data Kategori Peminatan</li>
            </ol>
          </nav>
        </div>
        <!--
        <div class="right">
          <Link :href="`/user/categories`" class="btn btn-primary btn-sm back-btn">
            <i class="bx bx-left-arrow-alt"></i> Kembali
          </Link>
        </div>
        -->
      </div>

      <hr class="divider" />

      <!-- Empty state -->
      <div v-if="!lessonCategories || !lessonCategories.length">
        <div class="card empty-card text-center">
          <div class="card-body py-5">
            <div class="empty-icon"><i class="bx bx-book-open"></i></div>
            <h5 class="fw-semibold mb-2">Kategori Peminatan Belum Tersedia</h5>
            <p class="text-muted mb-0">Silakan kembali nanti atau hubungi admin untuk pembaruan data.</p>
          </div>
        </div>
      </div>

      <!-- Grid kategori -->
      <div v-else class="row g-3 g-md-4">
        <div 
          v-for="lessonCategory in lessonCategories" 
          :key="lessonCategory.id || lessonCategory.category_id"
          class="col-xl-6 col-lg-6 col-md-12"
        >
          <div class="card cat-card h-100 p-3">
            <div class="card-body d-flex flex-column justify-content-between">
              
              <!-- Header -->
              <div class="d-flex align-items-center mb-2">
                <div class="icon-box me-3">
                  <i class="bx bx-layer"></i>
                </div>
                <div>
                  <h5 class="fw-bold mb-1 clamp-1">{{ lessonCategory.name }}</h5>
                  <p class="text-muted small clamp-2 mb-0">{{ lessonCategory.description }}</p>
                </div>
              </div>

              <!-- Footer -->
              <div class="d-flex justify-content-between align-items-center mt-auto">
                <span class="badge bg-light text-dark px-3 py-2 fw-semibold">
                  <i class="bx bx-book-content me-1"></i> Peminatan
                </span>
                <Link
                  :href="`/user/categories/${lessonCategory.category_id}/lesson-categories/${lessonCategory.id}/lessons`"
                  class="btn btn-primary btn-sm rounded-pill fw-semibold cta-btn"
                >
                  Selengkapnya <i class="bx bx-right-arrow-alt ms-1"></i>
                </Link>
              </div>

            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import LayoutUser from '../../../../Layouts/Layout.vue'
import { Link, Head } from '@inertiajs/inertia-vue3'

export default {
  layout: LayoutUser,
  components: { Link, Head },
  props: {
    lessonCategories: { type: [Array, Object], default: () => [] }
  }
}
</script>

<style>
/* ===== Breadcrumb ===== */
.breadcrumb-wrap {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  flex-wrap: wrap;
  margin-bottom: 1rem;
}
.breadcrumb-wrap .title {
  font-weight: 800;
  letter-spacing: 0.2px;
  font-size: 1.06rem;
}
.breadcrumb-wrap .left {
  display: flex;
  align-items: center;
  gap: 14px;
}
.breadcrumb .breadcrumb-item + .breadcrumb-item::before {
  content: "›";
}
.breadcrumb-wrap .home {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  border-radius: 10px;
  background: #f1f5f9;
  color: #0f172a;
  transition: transform 0.18s ease, background 0.18s ease;
}
.breadcrumb-wrap .home:hover {
  transform: translateY(-1px);
  background: #e2e8f0;
}
.back-btn {
  font-weight: 600;
  border-radius: 12px;
  box-shadow: 0 6px 16px rgba(2, 6, 23, 0.08);
  transition: all 0.2s ease-in-out;
}
.back-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 10px 24px rgba(2, 6, 23, 0.12);
  filter: brightness(1.02);
}
.divider {
  opacity: 0.25;
  margin: 0.4rem 0 1.4rem;
}

/* ===== Empty state ===== */
.empty-card {
  border: 1px dashed #e5e7eb;
  border-radius: 16px;
  background: linear-gradient(180deg, #fff 0%, #fafbfd 100%);
  box-shadow: 0 8px 20px rgba(2, 6, 23, 0.04);
}
.empty-icon {
  width: 64px;
  height: 64px;
  margin: 0 auto 12px;
  border-radius: 16px;
  display: grid;
  place-items: center;
  background: #eef2ff;
  color: #4f46e5;
  font-size: 30px;
}

/* ===== Kartu kategori ===== */
.cat-card {
  border: 1px solid #eef2f7;
  border-radius: 16px;
  background: #fff;
  transition: all 0.25s ease;
  box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
}
.cat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 14px 28px rgba(15, 23, 42, 0.1);
  border-color: #dbeafe;
}

/* Icon */
.icon-box {
  width: 52px;
  height: 52px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #eff6ff, #dbeafe);
  color: #2563eb;
  font-size: 1.5rem;
  box-shadow: 0 3px 8px rgba(37, 99, 235, 0.1);
}

/* CTA Button */
.cta-btn {
  transition: all 0.25s ease;
  padding: 0.45rem 1.1rem;
}
.cta-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(37, 99, 235, 0.25);
}

/* Clamp teks */
.clamp-1, .clamp-2 {
  overflow: hidden;
  display: -webkit-box;
  -webkit-box-orient: vertical;
}
.clamp-1 { -webkit-line-clamp: 1; line-clamp: 1; }
.clamp-2 { -webkit-line-clamp: 2; line-clamp: 2; }

/* Responsif */
@media (max-width: 768px) {
  .icon-box { width: 44px; height: 44px; font-size: 1.3rem; }
  .cat-card { padding: 1rem; }
}
</style>

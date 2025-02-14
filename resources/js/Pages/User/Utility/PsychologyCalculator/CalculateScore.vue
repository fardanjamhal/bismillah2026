<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Hitung Nilai Psikologi</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Kalkulator Psikologi</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Hitung Nilai</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card border-top border-0 border-3 border-primary">
                        <div class="card-header">
                            <div class="d-lg-flex align-items-center">
                                <p class="card-title"><b>{{ valueCategoryGroup.name }}</b></p>
                                <div class="ms-auto">
                                    <button class="btn btn-danger btn-sm mx-1" @click="resetForm">Reset</button>
                                    <Link href="/user/psychology-calculators" class="btn btn-primary btn-sm">Kembali</Link>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row" v-for="(valueCategory, index) in valueCategoryGroup.value_category" :key="index">
                                <label class="col-lg-4 col-md-4 col-form-label">{{ valueCategory.name }}</label>
                                <div class="col-lg-8 col-md-8">
                                    <div class="d-flex flex-wrap">
                                        <div class="d-flex align-items-center p-1" v-for="(detail_value_category, idx) in valueCategory.detail_value_category" :key="idx" :style="{ width: (100 / valueCategory.detail_value_category.length) + '%' }">
                                            <input :value="detail_value_category.final_grade" type="radio" class="btn-check" :name="'options-outlined-' + valueCategory.id" :id="'primary-outlined-' + valueCategory.id + '-' + detail_value_category.id" autocomplete="off">
                                            <label class="btn btn-outline-primary w-100 btn-sm" :for="'primary-outlined-' + valueCategory.id + '-' + detail_value_category.id">
                                                {{ detail_value_category.category_grade }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button class="btn btn-primary w-100 mt-3" @click="calculateTotal">Hitung Nilai</button>
                            <div v-if="finalScore" class="mt-3 text-center">
                                <div class="alert border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2" :class="{'alert-success': finalScore >= 61, 'alert-danger': finalScore < 61}">
                                    <h3 class="mb-3">Nilai Akhir</h3>
                                    <h3>{{ finalScore }}</h3>
                                    <h4>{{ finalScore >= 61 ? 'Memenuhi Syarat (MS)' : 'Tidak Memenuhi Syarat (TMS)' }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
//import layout user
import LayoutAdmin from '../../../../Layouts/Layout.vue';

// import Link
import { Link } from '@inertiajs/inertia-vue3';

// import Head from Inertia
import { Head } from '@inertiajs/inertia-vue3';

export default {
    // layout
    layout: LayoutAdmin,

    // register components
    components: {
        Link,
        Head,
    },

    // props
    props: {
        errors: Object,
        category: Object,
        valueCategoryGroup: Object,
    },

    data() {
        return {
            total: 0, // Reactive state to hold the total value
            finalScore: '', // Reactive state to hold the score category
        };
    },

    methods: {
        calculateTotal() {
            this.total = 0; // Reset total
            // Iterate through each value category
            this.valueCategoryGroup.value_category.forEach(valueCategory => {
                // Get the selected radio button for each value category
                const selected = document.querySelector(`input[name="options-outlined-${valueCategory.id}"]:checked`);
                if (selected) {
                    // Add the value of the selected radio button to total
                    this.total += parseFloat(selected.value);
                }
            });

            // Determine score category based on the total
            if (this.total >= -80 && this.total <= 0) {
                this.finalScore = 38;
            } else if (this.total >= -240 && this.total < -80) {
                this.finalScore = 34;
            } else if (this.total >= -400 && this.total < -240) {
                this.finalScore = 32;
            } else if (this.total < -400) {
                this.finalScore = 30;
            } else {
                this.finalScore = this.total;
            }
        },
        
        resetForm() {
            this.total = 0; // Reset total
            this.finalScore = ''; // Reset final score

            // Deselect all radio buttons
            const radioButtons = document.querySelectorAll('input[type="radio"]');
            radioButtons.forEach(radio => {
                radio.checked = false;
            });
        },
    },
};
</script>

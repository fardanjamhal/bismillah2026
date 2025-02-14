<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Tambah Kategori Penilaian</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Kategori Penilaian</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Kategori Penilaian</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <div v-if="Object.keys(errors).length > 0" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2">
                        <strong>Perhatian, pastikan inputan diisi dengan benar.</strong>
                        <ul class="mt-3">
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>
                    <div class="d-lg-flex align-items-center">
                        <div class="ms-auto">
                            <Link :href="`/admin/value-category-groups/${valueCategoryGroup.id}/value-categories`" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Mata Pelajaran</label>
                            <select v-model="form.lesson_id" :class="{ 'is-invalid': errors.lesson_id }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option v-for="(lesson, index) in lessons" :key="index" :value="lesson.id">
                                    {{ lesson.lesson_category.category.name }} - {{ lesson.lesson_category.name }} - {{ lesson.name }}</option>
                            </select>
                            <div v-if="errors.lesson_id" class="invalid-feedback">
                                {{ errors.lesson_id }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Nama Kategori Penilaian</label>
                            <input type="text" class="form-control" v-model="form.name" :class="{ 'is-invalid': errors.name }" placeholder="Nama">
                            <div v-if="errors.name" class="invalid-feedback">
                                {{ errors.name }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Jenis Penilaian</label>
                            <select v-model="form.assessment_type" :class="{ 'is-invalid': errors.assessment_type }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="2">Nilai = Total Jawaban Benar</option>
                                <option value="1">Nilai = (Total Benar / Total Soal) x 100</option>
                                <option value="4">Nilai = (Total Salah / Total Menjawab) x 100</option>
                                <option value="5">Nilai = Total Menjawab</option>
                                <option value="3">Nilai = Linear Slope</option>
                            </select>
                            <div v-if="errors.assessment_type" class="invalid-feedback">
                                {{ errors.assessment_type }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tambahkan Perhitungan Raw Score (RS)</label>
                            <select v-model="form.add_relative_score" :class="{ 'is-invalid': errors.add_relative_score }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                            <div v-if="errors.add_relative_score" class="invalid-feedback">
                                {{ errors.add_relative_score }}
                            </div>
                        </div>

                        <div class="col-12" v-if="form.add_relative_score == 1">
                            <label class="form-label">Rumus Perhitungan Relative Score (RS)</label>
                            <select v-model="form.relative_score_formulation" :class="{ 'is-invalid': errors.relative_score_formulation }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1"> 1). Jumlah Menjawab</option>
                                <option value="2"> 2). Jumlah Jawaban Benar</option>
                                <option value="3"> 3). Rasio Kesalahan Terhadap Jawaban (jumlah salah / jumlah menjawab / 100)</option>
                                <option value="4"> 4). Kemiringan Linear (liner slope)</option>
                                <option value="5"> 5). Persentase Kemiringan Linear (linear slope * 100)</option>
                                <option value="6"> 6). Persentase Jawaban Terhadap Jumlah Soal (jumlah menjawab / jumlah soal * 100)</option>
                                <option value="7"> 7). Persentase Jawaban Benar (jumlah Benar / jumlah menjawab * 100)</option>
                                <option value="8"> 8). Skor Berdasarkan Jawaban Benar (100 / jumlah soal * jumlah jawaban benar)</option>
                                <option value="9"> 9). Bobot Jawaban</option>
                            </select>
                            <div v-if="errors.relative_score_formulation" class="invalid-feedback">
                                {{ errors.relative_score_formulation }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tambahkan Perhitungan Weighted Score (WS)</label>
                            <select v-model="form.add_weighted_score" :class="{ 'is-invalid': errors.add_weighted_score }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                            <div v-if="errors.add_weighted_score" class="invalid-feedback">
                                {{ errors.add_weighted_score }}
                            </div>
                        </div>

                        <div class="col-12" v-if="form.add_weighted_score == 1">
                            <label class="form-label">Rumus Perhitungan Relative Score (RS)</label>
                            <select v-model="form.weighted_score_formulation" :class="{ 'is-invalid': errors.weighted_score_formulation }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1"> 1). Jumlah Menjawab</option>
                                <option value="2"> 2). Jumlah Jawaban Benar</option>
                                <option value="3"> 3). Rasio Kesalahan Terhadap Jawaban (jumlah salah / jumlah menjawab / 100)</option>
                                <option value="4"> 4). Kemiringan Linear (liner slope)</option>
                                <option value="5"> 5). Persentase Kemiringan Linear (linear slope * 100)</option>
                                <option value="6"> 6). Persentase Jawaban Terhadap Jumlah Soal (jumlah menjawab / jumlah soal * 100)</option>
                                <option value="7"> 7). Persentase Jawaban Benar (jumlah Benar / jumlah menjawab * 100)</option>
                                <option value="8"> 8). Skor Berdasarkan Jawaban Benar (100 / jumlah soal * jumlah jawaban benar)</option>
                                <option value="9"> 9). Bobot Jawaban</option>
                            </select>
                            <div v-if="errors.weighted_score_formulation" class="invalid-feedback">
                                {{ errors.weighted_score_formulation }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Bobot (Pengali)</label>
                            <input type="number" class="form-control" v-model="form.multiplier" :class="{ 'is-invalid': errors.multiplier }" placeholder="Bobot (Pengali)" min="0">
                            <div v-if="errors.multiplier" class="invalid-feedback">
                                {{ errors.multiplier }}
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm px-5">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout admin
    import LayoutAdmin from '../../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    //import reactive
    import { reactive } from 'vue';

    // import Swal
    import Swal from 'sweetalert2';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    import { Inertia } from '@inertiajs/inertia';

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
        },

        //props
        props: {
            errors: Object,
            lessons: Object,
            valueCategoryGroup: Object,
        },

        // initialization composition API
        setup(props) {
            const form = reactive({
                name: '',
                lesson_id: '',
                description: '',
                assessment_type: '',
                add_relative_score: '',
                relative_score_formulation: '',
                add_weighted_score: '',
                weighted_score_formulation: '',
                multiplier: '',
            });

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post(`/admin/value-category-groups/${props.valueCategoryGroup.id}/value-categories`, {
                    // data
                    value_category_group_id: props.valueCategoryGroup.id,
                    name: form.name,
                    lesson_id: form.lesson_id,
                    assessment_type: form.assessment_type,
                    add_relative_score: form.add_relative_score,
                    relative_score_formulation: form.relative_score_formulation,
                    add_weighted_score: form.add_weighted_score,
                    weighted_score_formulation: form.weighted_score_formulation,
                    multiplier: form.multiplier,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Kategori Penilaian Berhasil Disimpan!.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    },
                });
            }

            // return form state and submit method
            return {
                form,
                submit,
            }
        }
    }
</script>

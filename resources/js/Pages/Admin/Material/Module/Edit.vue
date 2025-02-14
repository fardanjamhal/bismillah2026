<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Edit Modul / Materi</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Modul / Materi</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Modul / Materi</li>
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
                            <Link href="/admin/modules" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Kategori Peminatan</label>
                            <select v-model="form.category_id" :class="{ 'is-invalid': errors.category_id }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option v-for="(category, index) in categories" :key="index" :value="category.id">
                                    {{ category.name }}</option>
                            </select>
                            <div v-if="errors.category_id" class="invalid-feedback">
                                {{ errors.category_id }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Kategori Member (Khusus Untuk Membership Bulanan, Kosongkan Jika Tidak Ada)</label>
                            <multiselect
                                v-model="form.member_category_ids"
                                :options="memberCategories"
                                :multiple="true"
                                label="name"
                                :close-on-select="true"
                                :allow-empty="true"
                                track-by="name"
                                placeholder="[ Pilih ]"
                            ></multiselect>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Judul</label>
                            <input type="text" class="form-control" v-model="form.title" :class="{ 'is-invalid': errors.title }" placeholder="Judul">
                            <div v-if="errors.title" class="invalid-feedback">
                                {{ errors.title }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Thumbnail (Optional)</label>
                            <input type="file" class="form-control" @input="form.thumbnail = $event.target.files[0]" :class="{ 'is-invalid': errors.thumbnail }" placeholder="Thumbnail">
                            <div v-if="errors.thumbnail" class="invalid-feedback">
                                {{ errors.thumbnail }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea cols="30" rows="10" class="form-control" v-model="form.description" :class="{ 'is-invalid': errors.description }" placeholder="Deskripsi Modul / Materi"></textarea>
                            <div v-if="errors.description" class="invalid-feedback">
                                {{ errors.description }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Urutan Ditampilkan</label>
                            <input type="text" class="form-control" v-model="form.order" :class="{ 'is-invalid': errors.order }" placeholder="Urutan Ditampilkan">
                            <div v-if="errors.order" class="invalid-feedback">
                                {{ errors.order }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tipe Harga</label>
                            <select v-model="form.price_type" :class="{ 'is-invalid': errors.price_type }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1">Gratis</option>
                                <option value="2">Berbayar</option>
                            </select>
                            <div v-if="errors.price_type" class="invalid-feedback">
                                {{ errors.price_type }}
                            </div>
                        </div>

                        <div class="col-12" v-if="form.price_type == 2">
                            <label class="form-label">Harga Sebelum Diskon</label>
                            <input type="number" class="form-control" v-model="form.price_before_discount" :class="{ 'is-invalid': errors.price_before_discount }" placeholder="Harga Sebelum Diskon" min="0">
                            <div v-if="errors.price_before_discount" class="invalid-feedback">
                                {{ errors.price_before_discount }}
                            </div>
                        </div>

                        <div class="col-12" v-if="form.price_type == 2">
                            <label class="form-label">Harga Sesudah Diskon</label>
                            <input type="number" class="form-control" v-model="form.price_after_discount" :class="{ 'is-invalid': errors.price_after_discount }" placeholder="Harga Sesudah Diskon" min="0">
                            <div v-if="errors.price_after_discount" class="invalid-feedback">
                                {{ errors.price_after_discount }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Status</label>
                            <select v-model="form.status" :class="{ 'is-invalid': errors.status }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="active">Aktive</option>
                                <option value="inactive">Inactive</option>
                                <option value="inprogress">Inprogress</option>
                            </select>
                            <div v-if="errors.status" class="invalid-feedback">
                                {{ errors.status }}
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

    import Multiselect from '@suadelabs/vue3-multiselect'

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
            Multiselect
        },

        //props
        props: {
            errors: Object,
            categories: Object,
            module: Object,
            memberCategories: Object,
            memberCategorySelected: Object,
        },

        // initialization composition API
        setup(props) {
            const form = reactive({
                category_id: props.module.category_id,
                title: props.module.title,
                thumbnail: props.module.thumbnail,
                description: props.module.description,
                order: props.module.order,
                price_type: props.module.price_type,
                price_before_discount: props.module.price_before_discount,
                price_after_discount: props.module.price_after_discount,
                status: props.module.status,
                member_category_ids: props.memberCategorySelected,
            });

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post(`/admin/modules/${props.module.id}`, {
                    forceFormData: true,
                    _method: 'put',
                    // data
                    category_id: form.category_id,
                    title: form.title,
                    thumbnail: form.thumbnail,
                    description: form.description,
                    order: form.order,
                    price_type: form.price_type,
                    price_before_discount: form.price_before_discount,
                    price_after_discount: form.price_after_discount,
                    status: form.status,
                    member_category_ids: form.member_category_ids
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Modul / MateriBerhasil Diupdate!.',
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

<style src="@suadelabs/vue3-multiselect/dist/vue3-multiselect.css"></style>
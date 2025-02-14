<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }}  - Edit Grup Sosial Media</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Grup Media Sosial</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Grup Media Sosial</li>
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
                            <Link href="/admin/social-groups" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" v-model="form.name" :class="{ 'is-invalid': errors.name }" placeholder="Nama">
                            <div v-if="errors.name" class="invalid-feedback">
                                {{ errors.name }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Logo</label>
                            <input type="file" class="form-control" @input="form.logo = $event.target.files[0]" :class="{ 'is-invalid': errors.logo }" placeholder="logo">
                            <div v-if="errors.logo" class="invalid-feedback">
                                {{ errors.logo }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Link</label>
                            <input type="text" class="form-control" v-model="form.link" :class="{ 'is-invalid': errors.link }" placeholder="Link">
                            <div v-if="errors.link" class="invalid-feedback">
                                {{ errors.link }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" v-model="form.description" :class="{ 'is-invalid': errors.description }" placeholder="Deskripsi" rows="10"></textarea>
                            <div v-if="errors.description" class="invalid-feedback">
                                {{ errors.description }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Urutan Ditampilkan</label>
                            <input type="number" min="0" class="form-control" v-model="form.order" :class="{ 'is-invalid': errors.order }" placeholder="Urutan Ditampilkan">
                            <div v-if="errors.order" class="invalid-feedback">
                                {{ errors.order }}
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
            socialGroup: Object
        },

        // initialization composition API
        setup(props) {
            const form = reactive({
                name: props.socialGroup.name,
                logo: '',
                link: props.socialGroup.link,
                description: props.socialGroup.description,
                order: props.socialGroup.order,
            });

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post(`/admin/social-groups/${props.socialGroup.id}`, {
                    forceFormData: true,
                    _method: 'put',
                    // data
                    name: form.name,
                    logo: form.logo,
                    link: form.link,
                    description: form.description,
                    order: form.order,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Group Sosial Media Berhasil Diupdate!.',
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

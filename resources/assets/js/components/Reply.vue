<template>
    <div :id="'reply-'+id" class="card card-default mt-4">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/' + data.owner.owner"
                       v-test="data.owner.name">
                    </a>
                    <small>said <span v-text="ago"></span></small>
                </h5>


                <div v-if="signedIn">
                    <favorite :reply="data"></favorite>
                </div>

            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>
                    <button class="btn btn-primary btn-sm">Update</button>
                    <button class="btn btn-link btn-sm" @click="editing = false">Cancel</button>
                </form>
            </div>

            <div v-else v-html="body"></div>
        </div>


        <div class="card-footer level" v-if="canUpdate">
            <button class="btn btn-default btn-sm mr-1" @click="editing = true">Edit</button>
            <button class="btn btn-danger btn-sm mr-1" type="button" @click="destroy">Delete</button>
        </div>

    </div>

</template>

<script>
    import Favorite from './Favorite.vue';
    import moment from 'moment';

    export default {
        props: ['data'],

        components: {
            Favorite
        },

        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            },

            canUpdate() {
                return this.authorize(user => this.data.user_id == user.id);
            },

            ago() {
                return moment(this.data.created_at + 'Z').fromNow();
            }
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.data.id, {
                    body: this.body
                }).catch(error => {
                    flash(error.response.data, 'danger');
                });

                this.editing = false;

                flash('Updated');
            },

            destroy() {
                axios.delete('/replies/' + this.data.id);

                this.$emit('deleted', this.data.id);
            }
        }
    }
</script>

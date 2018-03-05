<template>
    <div :id="'reply-'+id" class="card mt-4" :class="isBest ? 'text-white bg-success' : 'bg-light'">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/' + reply.owner.owner"
                       v-text="reply.owner.name">
                    </a>
                    <small>said <span v-text="ago"></span></small>
                </h5>


                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
                </div>

            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <wysiwyg v-model="body"></wysiwyg>
                    </div>
                    <button class="btn btn-primary btn-sm">Update</button>
                    <button class="btn btn-link btn-sm" @click="editing = false">Cancel</button>
                </form>
            </div>

            <div v-else v-html="body" class="trix-content"></div>
        </div>


        <div class="card-footer level" v-if="authorize('owns', reply) || authorize('owns', reply.issue)">
            <div v-if="authorize('owns', reply)">
                <button class="btn btn-default btn-sm mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-sm mr-1" type="button" @click="destroy">Delete</button>
            </div>

            <button class="btn default btn-sm ml-auto" v-if="authorize('owns', reply.issue)" type="button" @click="markBestReply">Best Reply?</button>
        </div>

    </div>

</template>

<script>
    import Favorite from './Favorite.vue';
    import moment from 'moment';

    export default {
        props: ['reply'],

        components: {
            Favorite
        },

        data() {
            return {
                editing: false,
                id: this.reply.id,
                body: this.reply.body,
                isBest: this.reply.isBest
            }
        },

        computed: {
            ago() {
                return moment(this.reply.created_at + 'Z').fromNow();
            }
        },

        created() {
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id);
            });
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.id, {
                    body: this.body
                }).catch(error => {
                    flash(error.response.data, 'danger');
                });

                this.editing = false;

                flash('Updated');
            },

            destroy() {
                axios.delete('/replies/' + this.id);

                this.$emit('deleted', this.id);
            },

            markBestReply() {
                axios.post('/replies/' + this.id + '/best');

                window.events.$emit('best-reply-selected', this.id);
            }
        }
    }
</script>

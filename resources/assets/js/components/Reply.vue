<template>
    <div :id="'reply-'+id" class="card card-default mt-4">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/' + data.owner.owner"
                       v-text="data.owner.name">
                    </a>
                    <small>said {{ data.created_at }}...</small>
                </h5>


                <div>
                    <!--<favorite :reply="{{ reply }}"></favorite>-->
                </div>

            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-primary btn-sm" @click="update">Update</button>
                <button class="btn btn-link btn-sm" @click="editing = false">Cancel</button>
            </div>

            <div v-else v-text="body"></div>
        </div>


        <div class="card-footer level">
            <button class="btn btn-default btn-sm mr-1" @click="editing = true">Edit</button>
            <button class="btn btn-danger btn-sm mr-1" @click="destroy">Delete</button>
        </div>

    </div>

</template>

<script>
    import Favorite from './Favorite.vue';

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

        methods: {
            update() {
                axios.patch('/replies/' + this.data.id, {
                    body: this.body
                });

                this.editing = false;

                flash('Updated');
            },

            destroy() {
                axios.delete('/replies/' + this.data.id);

                this.$emit('deleted', this.data.id);

                // $(this.$el).fadeOut(300, () => {
                //     flash('Your reply has been deleted.');
                // });

            }
        }
    }
</script>

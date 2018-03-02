<script>
    import Replies from '../components/Replies'
    import SubscribeButton from '../components/SubscribeButton'

    export default {
        name: "issue",

        props: ['issue'],

        components: {
            Replies,
            SubscribeButton
        },

        data() {
            return {
                repliesCount: this.issue.replies_count,
                locked: this.issue.locked,
                editing: false,
                form: {
                    title: this.issue.title,
                    description: this.issue.description
                }
            }
        },

        methods: {
            toggleLock() {
                axios[this.locked ? 'delete' : 'post']('/locked-issues/' + this.issue.slug);

                this.locked = ! this.locked;
            },

            cancel() {
                this.form = {
                    title: this.issue.title,
                    description: this.issue.description
                };

                this.editing = false;
            },

            update() {
                axios.patch('/issues/' + this.issue.category.slug + '/' + this.issue.slug, {
                    title: this.form.title,
                    description: this.form.description
                }).then(() => {
                    this.editing = false;

                    flash('Your issue has been updated.');
                });
            }
        }
    }
</script>


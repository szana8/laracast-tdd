<script>
    import Replies from "../components/Replies.vue";
    import SubscribeButton from "../components/SubscribeButton.vue";
    import Highlight from "../components/Highlight.vue";

    export default {
        props: ["issue"],
        components: { Replies, SubscribeButton, Highlight },
        data() {
            return {
                repliesCount: this.issue.replies_count,
                locked: this.issue.locked,
                pinned: this.issue.pinned,
                title: this.issue.title,
                description: this.issue.description,
                form: {},
                editing: false,
                feedback: "",
                errors: false
            };
        },
        created() {
            this.resetForm();
        },
        watch: {
            editing(enabled) {
                if (enabled) {
                    this.$modal.show("update-issue");
                } else {
                    this.$modal.hide("update-issue");
                }
            }
        },
        methods: {
            toggleLock() {
                let uri = `/locked-issues/${this.issue.slug}`;
                axios[this.locked ? "delete" : "post"](uri);
                this.locked = !this.locked;
            },
            togglePin() {
                let uri = `/pinned-issues/${this.issue.slug}`;
                axios[this.pinned ? "delete" : "post"](uri);
                this.pinned = !this.pinned;
            },
            update() {
                let uri = `/issues/${this.issue.category.slug}/${
                    this.issue.slug
                    }`;

                axios
                    .patch(uri, this.form)
                    .then(() => {
                        this.editing = false;
                        this.title = this.form.title;
                        this.description = this.form.description;
                        flash("Your issue has been updated.");
                    })
                    .catch(error => {
                        this.feedback = "Whoops, validation failed.";
                        this.errors = error.response.data.errors;
                    });
            },
            resetForm() {
                this.form = {
                    title: this.issue.title,
                    description: this.issue.description
                };
                this.editing = false;
                this.$modal.hide("update-issue");
            }
        }
    };
</script>
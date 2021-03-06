<template>
    <div class="comments-container">
        <!-- Show/hide comments buttons. -->
        <button v-if="!showComments" id="showComments" @click="showComments = true"  type="button" class="btn btn-primary btn-lg btn-block">Show all comments</button>
        <button v-else id="hideComments" @click="showComments = false"  type="button" class="btn btn-primary btn-lg btn-block">Hide all comments</button>
        
        <slide-up-down :active="showComments" :duration="1000">
            <span v-for="error in errors" v-bind:key="error[0]" class="invalid-feedback" role="alert">
                <strong>{{ error[0] }}</strong>
            </span>
            <vue-confirm-dialog></vue-confirm-dialog>

            <comment-form v-if="user" @load-overlay-comment='toggleLoadOverlay' :csrf="csrf" :saveCommentRoute="saveCommentRoute"
            @new-comment="addComment" :user="user" @show-login="$emit('show-login')" id="root-comment-form" :headers="headers"></comment-form>

            <comment-component v-if="comments" :csrf="csrf" :comments="comments" :headers="headers"
            @delete-comment="confirmCommentDeletion" :user="user" :save-comment-route="saveCommentRoute"
            @load-overlay-comment="toggleLoadOverlay" @new-comment="addComment" :level="0">
            </comment-component>
        </slide-up-down>
    </div>
</template>
<script>
    import CommentForm from "./CommentForm";
    import CommentComponent from "./CommentComponent";
    export default {
        name : 'CommentsComponent',
        components : {
            CommentForm, CommentComponent
        },
        props : ['getCommentsRoute', 'saveCommentRoute', 'user', 'csrf', 'headers'],
        data : function() {
            return {
                comments : null,
                errors : null,
                showComments : false,
            }
        },
        created() {
            this.getComments();
        },
        methods : {
            getComments() {
                this.toggleLoadOverlay(true);
                let comments = {};
                fetch(this.getCommentsRoute, {
                    method : 'GET',
                    headers : this.headers,
                })
                .then(data => data.json())
                .then(data => {
                    this.toggleLoadOverlay(false);
                    if(data['comments'])
                        this.comments = data['comments'];
                })
                .catch(error => {
                    this.$emit('toggle-load-overlay', false);
                    console.log(error);
                });
            },
            addComment(data) {
                if(data['trail'])
                    this.comments = data['comments'];
                else {
                    const comment = data['comment'];
                    Vue.set(this.comments, comment.id, comment);
                }
                //!!! Getting all comments for a single update.
                // Todo: spend another 3-4 hours and try to find more elegent way.
                // const comment = data['comment'];
                // if(data['trail']) {     
                //     const trail = JSON.parse(data['trail']);
                //     let branchToFill = null;
                //     trail.forEach(val => {
                //         if(!branchToFill)
                //             branchToFill = this.comments[val];
                //         else
                //             branchToFill = branchToFill['children'][val];
                //     });
                //     Vue.set(branchToFill['children'], comment.id, comment);
                //     this.toggleLoadOverlay(true);
                //     this.getComments();
                // }
            },
            toggleLoadOverlay(displayOverlay) {
                this.$emit('toggle-load-overlay', displayOverlay);
            },
            deleteComment(eventTarget) {
                const commentID = eventTarget.getAttribute('id').split("-").pop();
                event.preventDefault();
                this.errors = {};
                this.toggleLoadOverlay(true);
                fetch(this.saveCommentRoute + '/' + commentID, {
                    method : 'DELETE',
                    headers : this.headers,
                })
                .then(data => data.json())
                .then(data => {
                    if(!data['success'])
                        this.errors = data['errors'];
                    else {
                        if(data['trail']) {     
                            const trail = JSON.parse(data['trail']);
                            let elToDelete = null;
                            trail.forEach(val => {
                                if(!elToDelete)
                                    elToDelete = this.comments[val];
                                else
                                    elToDelete = elToDelete['children'][val];
                            });
                            Vue.delete(elToDelete['children'], data['comment_id']);
                        }
                        else
                            Vue.delete(this.comments, data['comment_id']);
                    }
                    this.toggleLoadOverlay(false);
                })
                .catch(error => {
                    this.toggleLoadOverlay(false);
                    console.log(error);
                });
            },
            confirmCommentDeletion(){
                //Preserve initial event target, as only it has the comment id that we'll need.
                let initialEventTarget = null;
                if(event.target.getAttribute('id') == null && event.target.classList.contains('glyphicon')) {
                    initialEventTarget = event.target.parentElement;
                }
                else
                    initialEventTarget = event.target;
                this.showDeleteConfirm = true;
                this.$confirm(
                    {
                        message: `Do you really want to delete this comment?`,
                        button: {
                            no: 'No',
                            yes: 'Yes'
                        },
                        /**
                         * Callback Function
                         * @param {Boolean} confirm 
                         */
                        callback: confirm => {
                            if (confirm) {
                                this.deleteComment(initialEventTarget);
                            }
                        }
                    }
                )
            },
        }
    }
</script>

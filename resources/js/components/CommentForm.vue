<template>
    <div class="container">
        <form method="POST" @submit="saveComment()">
            <input type="hidden" name="_token" :value="csrf">
            <div class="form-group row">
                <div class="col-md-6">
                    <!-- Different labels, depending on context. -->
                    <label v-if="isReply" for="name" class="col-md-4 col-form-label">Write your reply</label>
                    <label v-else for="name" class="col-md-4 col-form-label">Add a new comment</label>

                    <textarea v-model="content" type="text" class="form-control" :class="{ 'is-invalid' : 'content' in errors}" name="content" value="" required autocomplete="name" autofocus></textarea>
                    <div v-if="errors && !errors['content']" class="is-invalid"></div>
                    <span v-for="error in errors" v-bind:key="error[0]" class="invalid-feedback" role="alert">
                        <strong>{{ error[0] }}</strong>
                    </span>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary" :disabled="!user">Post</button>
                            <button v-if="isReply" @click="hideReplyForm" type="button" class="btn btn-secondary">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <p v-if="!user">You must <a href="#" @click="$emit('show-login')">login </a> to post a comment.</p>
    </div>
</template>

<script>
    export default {
        name : 'CommentForm',
        props : ['saveCommentRoute', 'csrf', 'user', 'repliedCommentId', 'isReply'],
        data : function() {
            return {
                content : '',
                errors : {},
            }
        },
        methods : {
            saveComment() {
                event.preventDefault();
                if(!this.user || !this.user.id) {
                    alert('You must login to post comments.');
                    return;
                }
                this.errors = {};
                this.$emit('load-overlay-comment', true);
                let data = {
                    'content' : this.content,
                    'user_id' : this.user.id
                }
                if(this.repliedCommentId !== undefined)
                    data['parent'] = this.repliedCommentId;
                fetch(this.saveCommentRoute, {
                    method : 'POST',
                    body : JSON.stringify(data),
                    headers : {
                        "Content-Type": "application/json; charset=utf-8",
                        'X-CSRF-TOKEN' : this.csrf,
                    },
                })
                .then(data => data.json())
                .then(data => {
                    if(!data['success'])
                        this.errors = data['errors'];
                    else {
                        this.$emit('new-comment', data);
                    }
                    this.$emit('load-overlay-comment', false);
                })
                .catch(error => {
                    this.$emit('load-overlay-comment', false);
                    console.log(error);
                });
            },
            hideReplyForm() {
                this.$emit('hide-reply-form');
            }
        }
    }
</script>

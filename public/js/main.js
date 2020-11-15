hljs.initHighlightingOnLoad()

new Vue({
    el: "#app",
    data: {
        title: "Post title",
        author: "Pedro Sérgio",
        category_id: -1,
        id: -1,
        inputID: '',
        key: "",
        body: "# welcome",
        categories: [],
        action: "categories",
        newCategoryName: "name your new category"
    },
    computed: {
        compiledMarkdown: function() {
            return marked(this.body, {
                sanitize: true,
                highlight: function(code) {
                    return hljs.highlightAuto(code).value;
                }, 
            });
        }
    },
    methods: {
        update: _.debounce(function(e) {
            this.body = e.target.value;
                document.querySelectorAll('pre code').forEach((block) => {
                    hljs.highlightBlock(block);
                });
            }, 
        300),

        loadEditPost() {
            axios({
                method: 'get',
                url: `/api/post/post.php?id=${this.inputID}`,
            }).then(response => {
                if (response.data === 'ACCESS DENIED') {
                    alert('incorrect input')
                } else {
                    if (response.data.author) {
                        this.id = response.data.id
                        this.title = response.data.title
                        this.body = response.data.body
                        this.category_id = response.data.category_id
                        this.author = response.data.author
                    } else {
                        alert('no post has been found.')
                    }
                }
            })
        },

        createCategory() {
            const { newCategoryName, key } = this;
            
            axios({
                method: 'post',
                url: '/api/category/create.php',
                data: { name: newCategoryName, key }
            }).then(response => {
                if (response.data === 'ACCESS DENIED') {
                    alert('incorrect input')
                } else {
                    alert('category created')
                    location.reload()
                }
            })
        },

        deleteCategory(id) {
            const { key } = this

            if (confirm('delete this category?')) {
                axios({
                    method: 'delete',
                    url: '/api/category/delete.php',
                    data: { id, key }
                }).then(response => {
                    if (response.data === 'ACCESS DENIED') {
                        alert('incorrect input')
                    } else {
                        alert('category deleted')
                        location.reload()
                    }
                })
            }
        },
        
        clear() {
            this.title = "Post title"
            this.author = "Pedro Sérgio"
            this.category_id = -1
            this.id = -1
            this.inputID = ''
            this.body = "" 
        },

        createPost() {
            const { title, author, category_id, body, key, id } = this;

            const url = id > 0? '/api/post/update.php' : '/api/post/create.php'
            const method = id > 0? 'PUT' : 'POST'

            const data = {
                title,
                author,
                category_id,
                body,
                key,
            }

            if (id > 0) {
                data.id = id
            }

            axios({
                method,
                url,
                data,
            }).then(response => {
                if (response.data === 'ACCESS DENIED') {
                    alert('incorrect input')
                } else {
                    alert(`post ${id > 0? 'updated' : 'created'}`)
                }
            })
        }
    },
    beforeMount() {
        axios({
            method: 'post',
            url: '/api/category/read.php',
        }).then(categories => {
            this.categories = categories.data.data
        })
    },
})
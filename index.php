<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://bootswatch.com/4/simplex/bootstrap.css">
    
    <script src="https://unpkg.com/vue"></script>
    <script src="https://unpkg.com/marked@0.3.6"></script>
    <script src="https://unpkg.com/lodash@4.16.0"></script>

    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.3.2/styles/sunburst.min.css">
    <!-- CSS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.3.2/highlight.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.0/axios.min.js"></script>

    <title>Create a post</title>
</head>
<body>
  <div id="app">
      <nav class="mb-4 navbar navbar-dark bg-dark">
        <div class="container">
          <a class="navbar-brand" href="#">Blog UI</a>
          <a class="text-primary" @click="action = 'categories'">categories</a>
          <a class="text-primary" @click="action = 'posts'">posts</a>
        </div>
      </nav>

      <div class="container" v-show="action === 'categories'">

      <div class="container">
        <h1>Create new category</h1>
        <div class="row">
          <div class="form-group col">
            <label for="title">Name</label>
            <input placeholder="category name" id="cat_name" v-model="newCategoryName" type="text" class="form-control">
          </div>
          <div class="form-group col">
            <label for="title">Password</label>
            <input placeholder="key" id="password" v-model="key" type="password" class="form-control">
          </div>
        </div>
        <div>
          <button @click="createCategory" class="btn btn-success">
            create
          </button>
        </div>
        <br>
      </div>

      <table class="table table-striped table-hover">
        <thead class="thead-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Category name</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="category in categories">
            <td>{{ category.id }}</td>
            <td>{{ category.name }}</td>
            <td>delete</td>
          </tr>
        </tbody>
      </table>
      </div>

      <div  class="container" v-show="action === 'posts'">
        <div class="row">
          <div class="form-group col">
            <label for="title">Title</label>
            <input placeholder="title" id="title" v-model="title" type="text" class="form-control">
          </div>
          <div class="form-group col">
            <label for="title">Author</label>
            <input placeholder="author" id="author" v-model="author" type="text" class="form-control">
          </div>
        </div>
        <div class="row">
          <div class="input-field col s6">
            <label for="category">Category</label>
            <select id="category" class="form-control">
              <option value="" disabled selected>Choose your option</option>
              <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
            </select>
          </div>
          <div class="input-field col s6">
            <label for="title">Password</label>
            <input placeholder="password" id="password" v-model="key" type="Password" class="form-control">
          </div>
        </div>
    
        <br>
        <div class="row">
          <div class="col">
            <textarea 
              style="height: 50vh; resize: none;" 
              class="input form-control" 
              :value="body" 
              @input="update"></textarea>
          </div>
          <div class="col">
            <div 
              style="height: 50vh; overflow: auto; border-radius: 5px;" 
              class="border p-2 bg-light"
              v-html="compiledMarkdown"
            ></div>
          </div>
        </div>

        <br>  
        <div>
          <button @click="createPost" class="btn btn-success">Create</button>
        </div>
      </div>
    </div>
<script>

    hljs.initHighlightingOnLoad();

    new Vue({
        el: "#app",
        data: {
            title: "Post title",
            author: "Pedro Sérgio",
            category_id: 1,
            key: "your key",
            body: "# welcome",
            categories: [],
            action: "posts",
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
          }, 300),

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
              }
            })
          },

          createPost() {
            const { title, author, category_id, body, key } = this;
            axios({
              method: 'post',
              url: '/api/post/create.php',
              data: {
                title,
                author,
                category_id,
                body,
                key,
                newCategoryName,
              }
            }).then(response => {
              if (response.data === 'ACCESS DENIED') {
                alert('incorrect input')
              } else {
                alert('post created')
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
</script>
</body>
</html>
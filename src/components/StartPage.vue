<template>
  <div class="start-page">
    <h1 class="cover-heading">Are you Blacklisted?</h1>
    <p class="lead">We will test your server domain or IP address against over 57 DNS based email blacklists.</p>
    <form v-on:submit.prevent="run">
      <div class="input-group input-group-lg">
        <input v-model="msg" type="text" class="form-control" placeholder="Server domain or IP address" aria-label="Recipient's username" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <button v-on:click="run" type="button" class="btn btn-danger">{{ buttonLabel }}</button>
        </div>
      </div>
    </form>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    <div v-if="total_items>-1">
      <p>{{ items.length }} of {{ total_items }} dnsbl-server</p>
      <table>
        <tr>
          <th>Host</th>
          <th>Response Time</th>
          <th>Listed</th>
        </tr>
        <tr v-for="item in items" :key="item.cnt" v-bind:item="item">
          <td>{{ item.host }}</td>
          <td>{{ item.time }}</td>
          <td>{{ item.listed }}</td>
        </tr>
      </table>
    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

  </div>
</template>

<script>

let evtSource = false

export default {
  name: 'StartPage',
  data () {
    return {
      msg: '94.16.152.175',
      actual_msg: '',
      total_items: -1,
      items: [],
      loading: false
    }
  },
  computed: {
    buttonLabel: function () {
      return (this.loading ? 'Testing...' : 'Test')
    }
  },
  methods: {
    run: function () {
      this.reset()

      var streamUrl = process.env.API_URL + '?ip=' + encodeURIComponent(this.msg)

      evtSource = new EventSource(streamUrl)
      this.loading = true

      var that = this

      evtSource.addEventListener('header', function (e) {
        var header = JSON.parse(e.data)
        that.total_items = header.total_items
        that.actual_msg = header.msg
      }, false)

      evtSource.addEventListener('item', function (e) {
        var item = JSON.parse(e.data)
        that.items.push(item)
      }, false)

      evtSource.addEventListener('close', function (e) {
        evtSource.close()
        that.loading = false
      }, false)
    },
    reset: function () {
      if (evtSource !== false) {
        evtSource.close()
      }
      this.loading = false
      this.items = []
      this.total_items = -1
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}
a {
  color: #42b983;
}
table {
  width:100%;
}
th {
  text-align:left;
}
</style>

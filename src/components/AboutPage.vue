<template>
  <div class="about">
    <h2>What is this about</h2>
    <p>This list above is of 80 DNS-based anti-spam databases. (DNS stands for Domain Name System, not for Do Not Solicit.) Most Internet service providers (ISPs) and web servers look to these lists for IP addresses linked to computers that send out spam or unwanted activity.</p>
    <p>Your IP address might have resulted in the blacklisted symbol () appearing next to a few databases. It may be that at one point your IP address was flagged for some reason and blocked by some servers. (It could be that someone who was once assigned that IP address was blacklisted.)</p>
    <p>Of course, if it seems that you're involved in spamming or other unwanted online behavior, your IP address could get blacklisted and blocked. It will get flagged and land on one or more of these databases. You'll be able to see that here at any time by running a new blacklist check.</p>
  </div>
</template>

<script>
export default {
  name: 'HelloWorld',
  data () {
    return {
      msg: 'Welcome to Your Vue.js App'
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>

<template>
  <div>
    <div class="totals">
      <div class="card total-orders">
        <div class="top">
          <span>
            <i class="fas fa-shopping-cart"></i>
            total orders
          </span>
        </div>
        <div class="bottom">
          <div class="result">2,265</div>
          <div class="percent positive">+3.5%</div>
        </div>
      </div>
      <div class="card total-earning">
        <div class="top">
          <span>
            <i class="fas fa-hand-holding-usd"></i>
            total earning
          </span>
        </div>
        <div class="bottom">
          <div class="result">$12,268.88</div>
          <div class="percent positive">+3.5%</div>
        </div>
      </div>
      <div class="card total-products">
        <div class="top">
          <span>
            <i class="fas fa-warehouse"></i>
            products
          </span>
        </div>
        <div class="bottom">
          <div class="result">2,265</div>
          <div class="percent negative">-6.8%</div>
        </div>
      </div>
      <div class="card total-clients">
        <div class="top">
          <span>
            <i class="fas fa-users"></i>
            total clients
          </span>
        </div>
        <div class="bottom">
          <div class="result">2,265</div>
          <div class="percent positive">+3.5%</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "dashboard",

  created() {
    window.setTitle('Dashboard & Analytics')
  }
}
</script>

<style scoped lang="scss">
.totals {
  width: 100%;
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  margin-top: 20px;
  .card {
    width: 23%;
    display: flex;
    flex-direction: column;
    box-shadow: 0 10px 15px 0 rgba(202, 215, 227, 0.11);
    border: solid 1px #edf2f6;
    padding: 20px 20px 24px;
    border-radius: 8px;
    height: 125px;
    background-color: #ffffff;
    .top {
      color: #c5cee0;
      display: flex;
      span {
        display: flex;
        align-items: center;
        text-transform: uppercase;
      }
      i {
        width: 36px;
        height: 36px;
        box-shadow: 0 10px 13px 0 rgba(210, 226, 242, 0.12);
        border: solid 1px #f6f8fa;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 15px;
        border-radius: 50%;
      }
    }
    .bottom {
      display: flex;
      flex-direction: row;
      align-items: center;
      .result {
        width: 75%;
        color: #222b45;
        font-size: 26px;
        margin-left: 10px;
        margin-top: 10px;
        font-family: 'SFProText-Bold', sans-serif;
      }
      .percent {
        border-radius: 8px;
        font-size: 12px;
        color: #ffffff;
        padding: 4px 7.5px;
        margin-top: 10px;
        &.positive {
          box-shadow: 0 4px 8px 0 rgba(102, 251, 202, 0.41);
          background-color: #00e096;
        }
        &.negative {
          box-shadow: 0 4px 8px 0 rgba(255, 197, 208, 0.43);
          background-color: #ff2d55;
        }
      }
    }
  }

}
</style>

$(document).ready(function () {
  // activeMenu();

  $(".slide-5").on("setPosition", function () {
    $(this).find(".slick-slide").height("auto");
    var slickTrack = $(this).find(".slick-track");
    var slickTrackHeight = $(slickTrack).height();
    $(this)
      .find(".slick-slide")
      .css("height", slickTrackHeight + "px");
    $(this)
      .find(".slick-slide > div")
      .css("height", slickTrackHeight + "px");
    $(this)
      .find(".slick-slide .category-wrapper")
      .css("height", slickTrackHeight + "px");
  });

  $(".breadcrumb-section").css("margin-top", $(".my-header").height() + "px");
  $(".my-home-slider").css("margin-top", $(".my-header").height() + "px");

  $(window).resize(function () {
    let height = $(".my-header").height();
    $(".breadcrumb-section").css("margin-top", height + "px");
    $(".my-home-slider").css("margin-top", height + "px");
  });

  // show more show less
  if ($(".category-item").length > 10) {
    $(".category-item:gt(9)").hide();
    $("#btn-view-more").show();
  }

  $("#btn-view-more").on("click", function () {
    $(".category-item:gt(9)").toggle();
    $(this).text() === "Xem thêm"
      ? $(this).text("Thu gọn")
      : $(this).text("Xem thêm");
  });

  $("li.my-layout-view > img").click(function () {
    $("li.my-layout-view").removeClass("active");
    $(this).parent().addClass("active");
  });

  $('#sort-form select[name="sort"]').change(function () {
    // console.log(getUrlParam('filter_price'));
    if (getUrlParam("filter_price")) {
      $("#sort-form").append(
        '<input type="hidden" name="filter_price" value="' +
          getUrlParam("filter_price") +
          '">'
      );
    }

    if (getUrlParam("search")) {
      $("#sort-form").append(
        '<input type="hidden" name="search" value="' +
          getUrlParam("search") +
          '">'
      );
    }

    $("#sort-form").submit();
  });

  setTimeout(function () {
    $("#frontend-message").toggle("slow");
  }, 4000);
});

function activeMenu() {
  // let controller = getUrlParam('controller') == null ? 'index' : getUrlParam('controller');
  // let action = getUrlParam('action') == null ? 'index' : getUrlParam('action');
  let dataActive = controller + "-" + action;
  $(`a[data-active=${dataActive}]`).addClass("my-menu-link active");
}

function getUrlParam(key) {
  let searchParams = new URLSearchParams(window.location.search);
  return searchParams.get(key);
}

$("#put-to-cart").on("click", function () {
  let quantity = $('input[name="quantity"]').val();
  let url = $(this).data("url");
  let cart = $("#cart").parent();
  url = url.replace("new_quantity", quantity);
  var cart_quantity = $("#total-quantity").text();

  //window.location.href = url;
  $.ajax({
    type: "GET",
    url,
    dataType: "json",
    success: function (response) {
      console.log(response);
      cart_quantity = parseInt(cart_quantity) + parseInt(quantity);
      $("#total-quantity").text(cart_quantity);
      cart.find("a").notify("Đã cập nhật giỏ hàng", {
        position: "bottom center",
        className: "success",
      });
    },
  });
});

$("#btn-view-more").on("click", function () {
  count = 5;
  var elements = document.getElementsByClassName(
    "custom-control custom-checkbox collection-filter-checkbox pl-0 category-item"
  );
  console.log(elements.length);
  for (var i = 0; i < elements.length; i++) {
    elements[i].style.display = "block";
  }
});

$(document).on("change", ".ajax-input-number", function () {
  let new_url = $(this).data("url");
  let parent = $(this).parent();
  let quantity = $(this).val();
  let searchParams = new URLSearchParams(new_url);
  let id = searchParams.get("book_id");
  let priceValue = searchParams.get("price");

  new_url = new_url.replace("new_value", quantity);

  hidden_quantity = $("input[data-quantity='" + id + "']").val();
  new_quantity = $("input[data-quantity='" + id + "']")
    .val()
    .replace(hidden_quantity, quantity);
  $("input[data-quantity='" + id + "']").val(new_quantity);

  // CHANGE TEXT THAT SHOW PRICE OF EACH ITEM
  $("#item_price_" + id).html(
    Intl.NumberFormat("vi-VN", { style: "currency", currency: "VND" }).format(
      priceValue * quantity
    )
  );

  $.ajax({
    type: "GET",
    url: new_url,
    dataType: "json",
    success: function (response) {
      if (response.status == "success") {
        let sum = 0;
        var totalQuantity = 0;
        for (var item_price in response.cart.price) {
          sum += response.cart.price[item_price];
        }
        for (var quantity in response.cart.quantity) {
          totalQuantity += parseInt(response.cart.quantity[quantity]);
        }
        $("#total-price").text(
          Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
          }).format(sum)
        );
        $("#total-quantity").text(totalQuantity);

        parent.find("input").notify("Đã cập nhật giỏ hàng", {
          position: "top center",
          className: "success",
        });
      }
    },
  });
});

// ICON AJAX ACTION: CART AND QUICKVIEW
$(document).on("click", ".ti-shopping-cart", function () {
  let new_url = $(this).data("url");
  let cart = $("#cart").parent();
  let quantity = $("#total-quantity").text();
  $.ajax({
    type: "GET",
    url: new_url,
    dataType: "json",
    success: function (response) {
      if (response.status == "success") {
        quantity++;
        $("#total-quantity").text(quantity);

        cart.find("a").notify("Đã cập nhật giỏ hàng", {
          position: "bottom center",
          className: "success",
        });
      }
    },
  });
});

$(".ti-close").on("click", function (e) {
  e.preventDefault();
  parent = $(this).parent();
  url = parent.attr("href");
  Swal.fire({
    title: "Bạn có chắc muốn bỏ sản phẩm khỏi giỏ hàng?",
    icon: "error",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#000",
    confirmButtonText: "Đồng ý",
    cancelButtonText: "Hủy",
  }).then((result) => {
    if (result.isConfirmed) {
      // $.ajax({
      //   url,
      //   dataType: "json",
      //   success: function(response) {

      //   }
      // });
      window.location.href = parent.attr("href");
    }
  });
});

//QUICK VIEW
$(".ti-search").on("click", function () {
  let url = $(this).data("url");
  $.ajax({
    url,
    dataType: "json",
    success: function (response) {
      if (response.status == "success") {
        console.log(response.cartLink);

        item = response.item;
        var sale_price = number_format(
          item.price - (item.price * item.sale_off) / 100
        );
        let del = $("<del> " + number_format(item.price) + "₫" + "</del>");
        $(".book-name").text(item.name);
        $(".book-price").text(sale_price + "₫");
        del.appendTo(".book-price");

        $(".book-picture").attr("src", response.picture);
        $(".book-description").html(item.short_description);

        $("#put-to-cart").on("click", function () {
          quantity = $('input[name="quantity"]').val();
          /* cartLink = $(this)
            .data("url")
            .replace("cart-url", response.cartLink + "&quantity=" + quantity); */
          cartLink = $(this).data("url");
          cartLink = response.cartLink + "&quantity=" + quantity;
          // var cart_quantity = $("#total-quantity").text();

          // $.ajax({
          //   type: "GET",
          //   url: cartLink,
          //   dataType: "json",
          //   success: function (response) {
          //     cart_quantity = parseInt(cart_quantity) + parseInt(quantity);
          //     $("#total-quantity").text(cart_quantity);
          //     cart.find("a").notify("Đã cập nhật giỏ hàng", {
          //       position: "bottom center",
          //       className: "success",
          //     });
          //     console.log(response);
          //   },
          // });
          window.location.href = cartLink;
        });
        $("#item-detail").attr("href", response.detailLink);
      }
    },
  });
});

function number_format(numberString) {
  numberString += "";
  var x = numberString.split("."),
    x1 = x[0],
    x2 = x.length > 1 ? "." + x[1] : "",
    rgxp = /(\d+)(\d{3})/;

  while (rgxp.test(x1)) {
    x1 = x1.replace(rgxp, "$1" + "," + "$2");
  }

  return x1 + x2;
}

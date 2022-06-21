module Components
  module ProductDiscount
    class ProductDiscountComponent < Middleman::Extension
      helpers do
        def product_discount(product, classes)
          return discount(product, classes) if product.old_price

          ""
        end

        def discount(product, classes)
          percentage = ((product.price - product.old_price) / product.old_price * 100).round(0)

          content_tag(:div, "#{percentage}%", class: "#{classes} px-3 py-1 bg-green-default text-white text-sm")
        end
      end
    end
  end
end

::Middleman::Extensions.register(:product_discount_component, Components::ProductDiscount::ProductDiscountComponent)

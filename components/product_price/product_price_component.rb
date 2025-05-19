module Components
  module ProductPrice
    class ProductPriceComponent < Middleman::Extension
      helpers do
        def product_price(product, classes = nil)
          return old_price(product, classes) + current_price(product) if product.data.old_price

          current_price(product)
        end

        def current_price(product)
          return current_real_price(product) + deposit(product) if product.data.deposit

          current_real_price(product)
        end

        def current_real_price(product)
          content_tag(:div, "#{price(product.data.price)}",
                      class: 'text-2xl font-bold whitespace-nowrap text-green-default')
        end

        def deposit(product)
          content_tag(:div, "+ #{price(product.data.deposit)} deposit",
                      class: 'text-sm whitespace-nowrap text-gray-400')
        end

        def old_price(product, classes)
          content_tag(:div, "#{price(product.data.old_price)}",
                      class: "#{classes} line-through whitespace-nowrap text-gray-400")
        end

        def price(price)
          return number_to_currency(price, precision: (price.round == price) ? 0 : 2, unit: '€', separator: ',', delimiter: '')
        end

        def total_price(product)
          total_price = product.data.price
          total_price += product.data.deposit if product.data.deposit

          return total_price
        end 
      end
    end
  end
end

::Middleman::Extensions.register(:product_price_component, Components::ProductPrice::ProductPriceComponent)

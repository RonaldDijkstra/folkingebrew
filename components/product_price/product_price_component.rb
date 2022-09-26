module Components
  module ProductPrice
    class ProductPriceComponent < Middleman::Extension
      helpers do
        def product_price(product)
          return old_price(product) + current_price(product) if product.old_price

          current_price(product)
        end

        def current_price(product)
          content_tag(:div, "#{price(product.price)}",
                      class: 'text-2xl font-bold whitespace-nowrap text-green-default')
        end

        def old_price(product)
          content_tag(:div, "#{price(product.old_price)}",
                      class: 'line-through whitespace-nowrap text-gray-400')
        end

        def price(price)
          return number_to_currency(price, precision: (price.round == price) ? 0 : 2, unit: '€', separator: ',', delimiter: '')
        end 
      end
    end
  end
end

::Middleman::Extensions.register(:product_price_component, Components::ProductPrice::ProductPriceComponent)

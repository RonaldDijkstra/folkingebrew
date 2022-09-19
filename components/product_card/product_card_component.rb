module Components
  module ProductCard
    class ProductCardComponent < Middleman::Extension
      helpers do
        def product_card(opts)
          product = opts[:product]

          concat(
            link_to("/store/#{product.slug}/", class: 'bg-white p-6') do
              content_tag(:figure, class: 'relative') do
                product_discount(product, 'absolute right-3 top-3') +
                image(product)
              end +
              content_tag(:div, class: 'flex') do
                content_tag(:h2, product.title, class: 'flex-grow font-bold mr-2') +
                content_tag(:div, product.price ? product_price(product) : "", class: 'flex flex-col items-end')
              end
            end
          )
        end

        private

        def image(product)
          image_url = product.images.first.url

          image_tag("#{image_url}?fm=webp&h=540&w=540&auto=enhance&fit=max",
                    alt: product.title,
                    class: 'mb-3',
                    height: '540',
                    width: '540',
                    loading: 'lazy',
                    srcset: "#{image_url}?fm=webp&h=540&w=540&auto=enhance&fit=max&dpr=1 1x,
                             #{image_url}?fm=webp&h=540&w=540&auto=enhance&fit=max&dpr=2 2x,
                             #{image_url}?fm=webp&h=540&w=540&auto=enhance&fit=max&dpr=3 3x")
        end
      end
    end
  end
end

::Middleman::Extensions.register(:product_card_component, Components::ProductCard::ProductCardComponent)

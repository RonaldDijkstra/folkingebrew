module Components
  module ProductCard
    class ProductCardComponent < Middleman::Extension
      helpers do
        def product_card(opts)
          product = opts[:product]

          concat(
            link_to("/store/#{product.slug}/", class: 'bg-white p-6') do
              image(product) +
              content_tag(:div, class: 'flex') do
                content_tag(:h2, product.title, class: 'flex-grow font-bold mr-2') +
                content_tag(:div, product_price(product), class: 'flex flex-col items-end')
              end
            end
          )
        end

        private

        def image(product)
          image_tag(product.images.first.url(fm: :webp, h: 540, w: 540),
                    alt: product.title,
                    class: 'mb-3',
                    height: '540',
                    width: '540',
                    loading: 'lazy')
        end
      end
    end
  end
end

::Middleman::Extensions.register(:product_card_component, Components::ProductCard::ProductCardComponent)

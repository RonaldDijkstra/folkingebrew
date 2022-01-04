module Components
  module FooterLink
    class FooterLinkComponent < Middleman::Extension
      helpers do
        def footer_link(opts)
          additional_classes = opts.dig(:html, :class) ? (opts[:html][:class]).to_s : ''
          link_to opts[:text],
                  (opts[:link]).to_s,
                  class: "transition-all block text-white hover:text-gray-300 mr-3 py-2
                          text-base font-medium #{additional_classes}
                          motion-reduce:transition-none motion-reduce:transform-none"
        end
      end
    end
  end
end

::Middleman::Extensions.register(:footer_link_component, Components::FooterLink::FooterLinkComponent)

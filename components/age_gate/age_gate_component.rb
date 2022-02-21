module Components
  module AgeGate
    class AgeGateComponent < Middleman::Extension
      helpers do
        def age_gate
          concat(content_tag(:div, class: container_classes, "data-rel": 'age-gate') do
                   content_tag(:div, class: inner_classes) do
                     inline_svg('logo-folkingebrew-age-gate.svg', alt: 'Folkingebrew', class: 'mb-6') +
                     content_tag(:div) do
                       button_consent +
                       button_decline
                     end
                   end
                 end)
        end

        def container_classes
          'age-gate hidden top-0 fixed bg-black w-full h-full left-0 z-50'
        end

        def inner_classes
          'text-center absolute text-white left-1/2 top-1/2
           transform -translate-y-1/2 -translate-x-1/2 md:max-w-10'
        end
      end

      helpers do
        def button_consent
          button(text: 'Over 18',
                 link: false,
                 type: :primary,
                 html: { class: 'age-gate-consent inline-block mr-3' })
        end

        def button_decline
          button(text: 'Under 18',
                 link: true,
                 href: 'https://www.nix18.nl/',
                 type: :default_gray,
                 html: { class: 'inline-block' })
        end
      end
    end
  end
end

::Middleman::Extensions.register(:age_gate_component, Components::AgeGate::AgeGateComponent)

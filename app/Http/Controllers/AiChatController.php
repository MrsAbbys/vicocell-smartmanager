<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\RepairOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiChatController extends Controller
{
    public function ask(Request $request): JsonResponse
    {
        $rawMessage = trim($request->input('message', ''));
        $message = mb_strtolower($rawMessage, 'UTF-8');
        $phone = env('VICOCELL_BOT_PHONE', '59170000000');

        if (empty($message)) {
            return response()->json([
                'reply' => '👋 ¡Hola! Soy el asistente virtual de **VICOCELL**. ¿En qué puedo ayudarte? Puedes pedirme celulares por marca, precio, accesorios o consultar por reparaciones de taller.'
            ]);
        }

        // =========================================================================
        // 1. SALUDOS Y EXPRESIONES COLOQUIALES
        // =========================================================================
        $greetings = ['hola', 'buenas', 'buen dia', 'buenos dias', 'buenas tardes', 'buenas noches', 'que tal', 'qué tal', 'como estas', 'cómo estás'];
        if (in_array($message, $greetings)) {
            return response()->json([
                'reply' => "👋 ¡Hola! Bienvenido a **VICOCELL SmartManager**.\n¿Qué estás buscando hoy? Puedes preguntarme por:\n\n• Marcas: _'Samsung'_, _'iPhone'_, _'Xiaomi'_\n• Presupuestos: _'Menor a 1500'_, _'Mayor a 1500'_\n• Accesorios: _'Cargadores'_, _'Audífonos'_, _'Protectores'_\n• Taller: _'Cambio de pantalla'_ o tu código _'REP-00001'_"
            ]);
        }

        // Preguntas sobre descuentos o rebajas
        if (str_contains($message, 'rebaja') || str_contains($message, 'descuento') || str_contains($message, 'cuanto lo ultimo') || str_contains($message, 'cuánto lo último') || str_contains($message, 'llapa') || str_contains($message, 'charque')) {
            return response()->json([
                'reply' => "🤝 ¡En **VICOCELL** siempre cuidamos a nuestros clientes! En compras al contado o combos de equipo + accesorio tenemos precios especiales.\n\nEscríbele directamente al dueño al **+{$phone}** para coordinar una rebaja especial en tu compra."
            ]);
        }

        // Preguntas sobre garantía
        if (str_contains($message, 'garantia') || str_contains($message, 'garantía') || str_contains($message, 'seguro')) {
            return response()->json([
                'reply' => "🛡️ **Garantía Asegurada:**\n• Equipos celulares: Garantía real de funcionamiento y homologación.\n• Servicios técnicos y repuestos: Garantía de laboratorio y calibración.\n\nPara consultar términos por modelo escríbenos al: **+{$phone}**."
            ]);
        }

        // =========================================================================
        // 2. SEGUIMIENTO DE REPARACIONES (Código REP-XXXXX)
        // =========================================================================
        if (preg_match('/rep-\d+/i', $message, $matches)) {
            $code = strtoupper($matches[0]);
            $repair = RepairOrder::where('tracking_code', $code)->first();

            if ($repair) {
                $statusText = "🔎 **Orden Encontrada: {$code}**\n"
                    . "• **Equipo:** {$repair->brand} {$repair->model}\n"
                    . "• **Estado:** {$repair->status}\n"
                    . "• **Diagnóstico:** " . ($repair->technical_diagnosis ?? 'En evaluación técnica') . "\n"
                    . "• **Saldo por pagar:** Bs. " . number_format($repair->balance, 2);

                if (str_contains($message, 'saldo') || str_contains($message, 'falta pagar') || str_contains($message, 'cuanto')) {
                    $statusText .= "\n\n💰 Debes cancelar **Bs. " . number_format($repair->balance, 2) . "** al momento de retirar tu equipo.";
                }

                $statusText .= "\n\nPara agilizar la entrega comunícate al: **+{$phone}**.";
                return response()->json(['reply' => $statusText]);
            }

            return response()->json([
                'reply' => "No encontré la orden **{$code}** en el sistema. El dueño está en línea y puede ayudarte a buscarla manualmente en recepción al **+{$phone}**."
            ]);
        }

        // =========================================================================
        // 3. DIAGNÓSTICOS TÉCNICOS DE TALLER
        // =========================================================================
        if (str_contains($message, 'pantalla') || str_contains($message, 'vidrio') || str_contains($message, 'táctil') || str_contains($message, 'tactil') || str_contains($message, 'display') || str_contains($message, 'quebró') || str_contains($message, 'rompió')) {
            return response()->json([
                'reply' => "🛠️ **Reparación de Pantallas:**\nInstalamos módulos originales y pantallas OLED con garantía de calibración táctil. El tiempo estimado de entrega es de 2 a 4 horas.\n\n📱 Cotiza el repuesto para tu modelo escribiéndonos a WhatsApp: **+{$phone}**."
            ]);
        }

        if (str_contains($message, 'pin de carga') || str_contains($message, 'puerto de carga') || (str_contains($message, 'pin') && str_contains($message, 'carga'))) {
            return response()->json([
                'reply' => "🔌 **Pin de Carga:**\nRealizamos reconstrucción y sustitución de puertos Tipo-C, micro-USB y Lightning. Incluye prueba de amperaje.\n\nTrae tu equipo a nuestro taller o comunícate al: **+{$phone}**."
            ]);
        }

        if (str_contains($message, 'bateria') || str_contains($message, 'batería') || str_contains($message, 'no prende') || str_contains($message, 'apaga') || str_contains($message, 'descarga') || str_contains($message, 'no carga')) {
            return response()->json([
                'reply' => "⚡ **Baterías y Fallas de Energía:**\nContamos con baterías de alta capacidad y hacemos pruebas de consumo en placa madre.\n\nAgenda tu chequeo escribiendo al: **+{$phone}**."
            ]);
        }

        if (str_contains($message, 'agua') || str_contains($message, 'mojó') || str_contains($message, 'mojado') || str_contains($message, 'humedad')) {
            return response()->json([
                'reply' => "🚨 **¡Atención por Humedad!**\n1. NO conectes el cargador ni lo fuerces a encender.\n2. Tráelo de inmediato a VICOCELL para baño químico en tina de ultrasonido.\n\nEscríbenos de urgencia al: **+{$phone}**."
            ]);
        }

        if (str_contains($message, 'mantenimiento') || str_contains($message, 'limpieza') || str_contains($message, 'preventivo')) {
            return response()->json([
                'reply' => "🧰 **Mantenimiento Preventivo:**\nLimpieza ultrasónica de circuitos, despeje de micro-parlantes y sustitución térmica.\n\nTurnos de atención al: **+{$phone}**."
            ]);
        }

        // =========================================================================
        // 4. FORMAS DE PAGO, ENVÍOS, UBICACIÓN Y HORARIOS
        // =========================================================================
        if (str_contains($message, 'qr') || str_contains($message, 'transferencia') || str_contains($message, 'efectivo') || str_contains($message, 'pago') || str_contains($message, 'pagar')) {
            return response()->json([
                'reply' => "💳 **Métodos de Pago:**\nAceptamos transferencias por **código QR** y pago en **efectivo / contra entrega** en tienda."
            ]);
        }

        if (str_contains($message, 'envio') || str_contains($message, 'envío') || str_contains($message, 'domicilio') || str_contains($message, 'delivery')) {
            return response()->json([
                'reply' => "🛵 **Envíos y Retiros:**\nContamos con entregas a domicilio locales y retiro en tienda física. Coordinaciones al: **+{$phone}**."
            ]);
        }

        if (str_contains($message, 'donde') || str_contains($message, 'dónde') || str_contains($message, 'ubicacion') || str_contains($message, 'ubicación') || str_contains($message, 'horario') || str_contains($message, 'abierto')) {
            return response()->json([
                'reply' => "📍 **VICOCELL SmartManager:**\n• Lunes a Sábado: 08:30 a 12:30 y 14:30 a 20:00.\n• Domingos: 09:00 a 13:00 (guardia técnica).\n\nPara solicitar ubicación GPS exacta escribe al: **+{$phone}**."
            ]);
        }

        if (str_contains($message, 'humano') || str_contains($message, 'asesor') || str_contains($message, 'vendedor') || str_contains($message, 'contacto') || str_contains($message, 'whatsapp')) {
            return response()->json([
                'reply' => "📞 **Atención Directa:**\nEl dueño y el equipo técnico están disponibles en la línea directa: **+{$phone}**."
            ]);
        }

        // =========================================================================
        // 5. FILTRO POR PRECIO: MENOR / MAYOR A X MONTO
        // =========================================================================
        preg_match('/(?:menor|menos|hasta|abajo|máximo|maximo)\s*(?:a|de)?\s*(\d+)/i', $message, $matchMenor);
        preg_match('/(?:mayor|mas|más|arriba|desde|mínimo|minimo)\s*(?:a|de)?\s*(\d+)/i', $message, $matchMayor);

        // Caso: Menor a un precio específico
        if (!empty($matchMenor[1])) {
            $limit = (float) $matchMenor[1];
            $prods = Product::where('is_active', true)
                ->where('price', '<=', $limit)
                ->orderBy('price', 'desc')
                ->take(4)
                ->get();

            if ($prods->isNotEmpty()) {
                return response()->json([
                    'reply' => $this->formatList("💰 **Celulares y productos con precio menor o igual a Bs. " . number_format($limit, 2) . ":**", $prods, $phone)
                ]);
            } else {
                return response()->json([
                    'reply' => "Por el momento no contamos con equipos por debajo de Bs. {$limit}. Pero el dueño está en línea y con gusto te ofrecerá opciones o facilidades al **+{$phone}**."
                ]);
            }
        }

        // Caso: Mayor a un precio específico
        if (!empty($matchMayor[1])) {
            $limit = (float) $matchMayor[1];
            $prods = Product::where('is_active', true)
                ->where('price', '>=', $limit)
                ->orderBy('price', 'asc')
                ->take(4)
                ->get();

            if ($prods->isNotEmpty()) {
                return response()->json([
                    'reply' => $this->formatList("💎 **Equipos de gama alta y rendimiento superior a Bs. " . number_format($limit, 2) . ":**", $prods, $phone)
                ]);
            } else {
                return response()->json([
                    'reply' => "No encontramos equipos por encima de esa cifra en stock inmediato. El dueño te puede cotizar modelos flagships a pedido al **+{$phone}**."
                ]);
            }
        }

        // Búsqueda de celulares más baratos o económicos
        if (str_contains($message, 'barato') || str_contains($message, 'economico') || str_contains($message, 'económico')) {
            $prods = Product::where('is_active', true)
                ->where(function ($q) {
                    $q->whereHas('category', function ($cat) {
                        $cat->where('name', 'like', '%Celular%');
                    })->orWhere('name', 'like', '%Phone%')->orWhere('name', 'like', '%Galaxy%')->orWhere('name', 'like', '%Redmi%');
                })
                ->orderBy('price', 'asc')
                ->take(3)
                ->get();

            if ($prods->isNotEmpty()) {
                return response()->json([
                    'reply' => $this->formatList("💰 **Opciones más económicas disponibles en tienda:**", $prods, $phone)
                ]);
            }
        }

        // =========================================================================
        // 6. FILTRO POR MARCA SOLICITADA
        // =========================================================================
        $brands = [
            'samsung'  => ['samsung', 'galaxy'],
            'apple'    => ['iphone', 'apple'],
            'xiaomi'   => ['xiaomi', 'redmi', 'poco'],
            'motorola' => ['motorola', 'moto'],
            'huawei'   => ['huawei', 'honor'],
            'infinix'  => ['infinix'],
            'tecno'    => ['tecno', 'spark', 'camon'],
            'realme'   => ['realme']
        ];

        foreach ($brands as $brandKey => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($message, $kw)) {
                    $matchedBrandProds = Product::where('is_active', true)
                        ->where(function ($q) use ($keywords) {
                            foreach ($keywords as $w) {
                                $q->orWhere('name', 'like', "%{$w}%")
                                  ->orWhere('description', 'like', "%{$w}%");
                            }
                        })
                        ->orderBy('price', 'desc')
                        ->take(5)
                        ->get();

                    if ($matchedBrandProds->isNotEmpty()) {
                        $brandTitle = ucfirst($brandKey);
                        return response()->json([
                            'reply' => $this->formatList("📱 **Celulares y equipos {$brandTitle} disponibles:**", $matchedBrandProds, $phone)
                        ]);
                    } else {
                        return response()->json([
                            'reply' => "Actualmente no nos queda stock en tienda de la marca **" . ucfirst($brandKey) . "**, pero el dueño está en línea y con gusto te avisará cuándo llegan nuevos lotes o gestionará tu pedido al **+{$phone}**."
                        ]);
                    }
                }
            }
        }

        // =========================================================================
        // 7. FILTRO POR ACCESORIOS
        // =========================================================================
        $accessoryKeywords = ['accesorio', 'accesorios', 'cargador', 'cargadores', 'cable', 'cables', 'audifono', 'audifonos', 'audífono', 'audífonos', 'airpod', 'airpods', 'funda', 'fundas', 'case', 'cases', 'protector', 'protectores', 'vidrio templado', 'hidrogel'];
        $isAccessoryQuery = false;

        foreach ($accessoryKeywords as $akw) {
            if (str_contains($message, $akw)) {
                $isAccessoryQuery = true;
                break;
            }
        }

        if ($isAccessoryQuery) {
            $accessories = Product::where('is_active', true)
                ->where(function ($q) {
                    $q->whereHas('category', function ($cat) {
                        $cat->where('name', 'like', '%Accesorio%')
                            ->orWhere('name', 'like', '%Audio%')
                            ->orWhere('name', 'like', '%Cargador%')
                            ->orWhere('name', 'like', '%Repuesto%');
                    })
                    ->orWhere('name', 'like', '%Cargador%')
                    ->orWhere('name', 'like', '%Cable%')
                    ->orWhere('name', 'like', '%Audifono%')
                    ->orWhere('name', 'like', '%AirPod%')
                    ->orWhere('name', 'like', '%Protector%')
                    ->orWhere('name', 'like', '%Vidrio%')
                    ->orWhere('name', 'like', '%Case%');
                })
                ->orderBy('price', 'asc')
                ->take(5)
                ->get();

            if ($accessories->isNotEmpty()) {
                return response()->json([
                    'reply' => $this->formatList("🎧 **Accesorios disponibles en stock:**", $accessories, $phone)
                ]);
            }
        }

        // =========================================================================
        // 8. LISTADO GENERAL: "CELULARES" O "DISPOSITIVOS"
        // =========================================================================
        if ($message === 'celulares' || $message === 'celular' || str_contains($message, 'que celulares') || str_contains($message, 'qué celulares') || str_contains($message, 'telefonos') || str_contains($message, 'teléfonos')) {
            $phones = Product::where('is_active', true)
                ->where(function ($q) {
                    $q->whereHas('category', function ($cat) {
                        $cat->where('name', 'like', '%Celular%')
                            ->orWhere('name', 'like', '%Teléfono%')
                            ->orWhere('name', 'like', '%Móvil%');
                    })
                    ->orWhere('name', 'like', '%iPhone%')
                    ->orWhere('name', 'like', '%Samsung%')
                    ->orWhere('name', 'like', '%Xiaomi%')
                    ->orWhere('name', 'like', '%Motorola%');
                })
                ->orderBy('price', 'desc')
                ->take(5)
                ->get();

            if ($phones->isNotEmpty()) {
                return response()->json([
                    'reply' => $this->formatList("📱 **Modelos de celulares listos para entrega inmediata:**", $phones, $phone)
                ]);
            }
        }

        // =========================================================================
        // 9. BÚSQUEDA LIBRE EN BASE DE DATOS
        // =========================================================================
        $cleanWords = array_filter(explode(' ', preg_replace('/[^\p{L}\p{N}\s]/u', '', $message)), function ($w) {
            return mb_strlen($w, 'UTF-8') > 2 && !in_array($w, ['los', 'las', 'que', 'una', 'para', 'con', 'por', 'del', 'tienen', 'quiero', 'busca', 'busco', 'dime']);
        });

        if (!empty($cleanWords)) {
            $query = Product::where('is_active', true);
            $query->where(function ($q) use ($cleanWords) {
                foreach ($cleanWords as $cw) {
                    $q->orWhere('name', 'like', "%{$cw}%")
                      ->orWhere('description', 'like', "%{$cw}%")
                      ->orWhereHas('category', function ($cat) use ($cw) {
                          $cat->where('name', 'like', "%{$cw}%");
                      });
                }
            });

            $results = $query->take(4)->get();
            if ($results->isNotEmpty()) {
                return response()->json([
                    'reply' => $this->formatList("✨ **Encontré estas opciones que te pueden interesar:**", $results, $phone)
                ]);
            }
        }

        // =========================================================================
        // 10. RESPUESTA DE CONTINGENCIA (DUEÑO EN LÍNEA)
        // =========================================================================
        return response()->json([
            'reply' => "💬 **El dueño de VICOCELL está en línea ahora mismo.**\n\nNo tengo una respuesta predeterminada para tu consulta, pero el encargado te responderá con gusto para brindarte asesoría personalizada, precios o cotizaciones:\n\n📱 **Escríbele directamente a WhatsApp:** **+{$phone}**\n*(Atención inmediata para resolver tu duda)*"
        ]);
    }

    /**
     * Da formato amigable a la lista de productos encontrados.
     */
    private function formatList(string $title, $products, string $phone): string
    {
        $out = "{$title}\n\n";
        foreach ($products as $item) {
            $out .= "• **{$item->name}** — Bs. " . number_format($item->price, 2) . " (Stock: {$item->stock})\n";
        }
        $out .= "\n🛍️ Puedes comprarlo en línea o apartarlo directamente escribiendo al **+{$phone}**.";
        return $out;
    }
}

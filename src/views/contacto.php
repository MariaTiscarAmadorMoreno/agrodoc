   <!-- Contenido principal -->
    <main id="main" role="main"> 
      <div class="container_form">
        <form id="contactForm" class="contact-form" novalidate>
            <h2>SI NECESITAS AYUDA CONTACTA CON NOSOTROS</h2>            
        
            <!-- Nombre y apellidos -->
            <div class="form-group">
                <label for="nombre">Nombre <span>*</span></label>
                <input type="text" id="nombre" name="nombre">
            </div>
        
            <div class="form-group">
                <label for="apellidos">Apellidos <span>*</span></label>
                <input type="text" id="apellidos" name="apellidos">
            </div>
        
            <!-- Nombre de la empresa -->
            <div class="form-group">
                <label for="empresa">Nombre de la empresa <span>*</span></label>
                <input type="text" id="empresa" name="empresa">
            </div>
        
            <!-- Correo electrónico -->
            <div class="form-group">
                <label for="email">Correo corporativo <span>*</span></label>
                <input type="email" id="email" name="email">
            </div>
        
            <!-- CIF -->
            <div class="form-group">
                <label for="cif">CIF de la empresa <span>*</span></label>
                <input type="text" id="cif" name="cif">
            </div>
        
            <!-- Número de teléfono -->
            <div class="form-group">
                <label for="telefono">Número de teléfono <span>*</span></label>
                <input type="tel" id="telefono" name="telefono" pattern="[0-9]{9}">
            </div>
        
            <!-- Departamento -->
            <div class="form-group">
                <label for="departamento">Departamento <span>*</span></label>
                <select id="departamento" name="departamento">
                    <option value="">-- Ninguno --</option>
                    <option value="ventas">Ventas</option>
                    <option value="atencion-cliente">Atención al cliente</option>
                    <option value="soporte">Soporte</option>
                    <option value="marketing">Marketing</option>
                </select>
            </div>
        
            <!-- Cargo -->
            <div class="form-group">
                <label for="cargo">Cargo <span>*</span></label>
                <select id="cargo" name="cargo" required>
                    <option value="">-- Ninguno --</option>
                    <option value="director">Director</option>
                    <option value="gerente">Gerente</option>
                    <option value="empleado">Empleado</option>
                </select>
            </div>
        
            <!-- Servicio que interesa -->
            <div class="form-group">
                <label for="servicio">Servicio que te interesa <span>*</span></label>
                <select id="servicio" name="servicio" required>
                    <option value="">-- Ninguno --</option>
                    <option value="gestión-documental">Gestión Documental</option>
                    <option value="facturación-electrónica">Facturación Electrónica</option>
                    <option value="homologación">Homologación de Empresas</option>
                    <option value="compras">Herramienta de Compras</option>
                </select>
            </div>
        
            <!-- Comentarios -->
            <div class="form-group">
                <label for="mensaje">¿En qué podemos ayudarte? <span>*</span></label>
                <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
            </div>
        
            <!-- Consentimiento -->
            <div class="form-group">
                <p class="consentimiento">
                    Sus datos serán tratados por AgroDoc para atender su solicitud de información y en su caso para enviar comunicaciones comerciales. 
                    Podrá ejercer sus derechos mediante correo postal a C. Proción, 7, 28023 Madrid, o por correo electrónico a 
                    <a href="mailto:dpo@agrodoc.com">dpo@agrodoc.com</a> aportando DNI o documento equivalente, identificándose como usuario del servicio de AgroDoc.
                </p>
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" id="consentimiento" name="consentimiento" required>
                <label for="consentimiento">Acepto las políticas de privacidad de AgroDoc <span>*</span></label>
            </div>        
            <!-- Deseo recibir comunicaciones comerciales -->
            <div class="form-group checkbox-group">
                <input type="checkbox" id="comercial" name="comercial">
                <label for="comercial">Deseo recibir comunicaciones comerciales de AgroDoc</label>
            </div>
        
            <!-- Botón de enviar -->
            <button type="submit" class="btn-submit">Enviar</button>
        </form>
      </div>
  </main> 
  <script src="/assets/js/contacato.js"></script>

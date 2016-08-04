      <div class="content-header">
        <h2 class="content-header-title relatorio atividade-diaria">Relatório de Atividade Diária</h2>
        <ol class="breadcrumb">
          <li><a href="./index.html">Home</a></li>
          <li><a href="javascript:;">Relatórios</a></li>
          <li class="active">Atividade Diária</li>
        </ol>
      </div> <!-- /.content-header -->

      

      <div class="row">
        <div class="col-md-12">

          <div class="row">
            <div class="col-md-3">
              <select name="ano" class="form-control">
                <option value="2014">2014</option>
                <option value="2015">2015</option>
              </select> 
            </div>
            <div class="col-md-6">
              <select name="mes" class="form-control">
                <option value="01">Janeiro</option>
                <option value="02">Fevereiro</option>
                <option value="03">Março</option>
                <option value="04">Abril</option>
                <option value="05">Maio</option>
                <option value="06">Junho</option>
                <option value="07">Julho</option>
                <option value="08">Agosto</option>
                <option value="09">Setembro</option>
                <option value="11">Outubro</option>
                <option value="10">Novembro</option>
                <option value="12">Dezembro</option>
              </select>                
            </div>
            <div class="col-md-3">
              <button type="button" class="btn btn-default btn-block">
                <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Buscar
              </button>
            </div>
          </div>

          <div class="portlet-content">
              <div class="table-responsive">
              <table 
                class="table table-striped large-table able-bordered table-hover table-highlight table-checkable atividade-diaria" 
                data-provide="datatable" 
                data-display-rows="20"
                data-info="true"
                data-search="true"
                data-length-change="true"
                data-paginate="true"
              >
                  <thead>
                    <tr>
                      <th data-sortable="true">Dia</th>
                      <th data-sortable="true">Convites</th>
                      <th data-sortable="true">Entraram</th>
                      <th data-sortable="true">Novos Acessos</th>
                      <th data-sortable="true">Novas Indicações</th>
                      <th data-sortable="true">Clientes Repetidores</th>
                      <th data-sortable="true">Número de Acessos</th>
                      <th data-sortable="true">Valor Total de Acessos</th>
                      <th data-sortable="true">Lucro Acessos</th>
                      <th data-sortable="true">P.V Acessos</th>
                      <th data-sortable="true">Lucro Produtos</th>
                      <th data-sortable="true">Total Vendas</th>
                      <th data-sortable="true">Total P.V</th>
                      <th data-sortable="true">Lucro Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td><input type="text" value="3" class="form-it"></td>
                      <td><input type="text" value="2" class="form-it"></td>
                      <td>4</td>
                      <td>3</td>
                      <td>8</td>
                      <td>1,00</td>
                      <td>R$ 10,00</td>
                      <td>R$ 4,48</td>
                      <td>1,86</td>
                      <td>R$ 5,96</td>
                      <td>R$ 31,00</td>
                      <td>6,06</td>
                      <td>R$ 10,44</td>
                    </tr>
                    <tr>
                      <td>10</td>
                      <td><input type="text" value="0" class="form-it"></td>
                      <td><input type="text" value="0" class="form-it"></td>
                      <td>1</td>
                      <td>1</td>
                      <td>11</td>
                      <td>1,00</td>
                      <td>R$ 10,00</td>
                      <td>R$ 4,49</td>
                      <td>1,86</td>
                      <td>R$ 61,71</td>
                      <td>R$ 148,00</td>
                      <td>31,06</td>
                      <td>R$ 66,20</td>
                    </tr>
                  </tbody>
                </table>
              </div> <!-- /.table-responsive -->
          </div> <!-- /.portlet-content -->

        </div> <!-- /.col -->

      </div> <!-- /.row -->
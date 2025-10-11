import 'package:flutter/material.dart';
import 'package:sam_app/data/models/curso_model.dart';
import 'package:sam_app/data/repositories/curso_repository.dart';
import 'package:sam_app/data/services/register_service.dart';
import 'package:sam_app/presentation/widgets/snack/top_snack_bar.dart';

class RegisterPage extends StatefulWidget {
  final int instituicaoId;
  final String nomeInstituicao;
  final String dominioInstituicao;

  const RegisterPage({
    super.key,
    required this.instituicaoId,
    required this.nomeInstituicao,
    required this.dominioInstituicao,
  });

  @override
  State<RegisterPage> createState() => _RegisterPageState();
}

class _RegisterPageState extends State<RegisterPage> {
  final _formKey = GlobalKey<FormState>();

  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();

  int? _selectedCurso;
  int? _selectedAnoInicio;
  int? _selectedAnoFim;
  bool _loading = false;
  String? _errorMessage;

  List<CursoModel> _cursos = [];
  final CursoRepository _cursoRepo = CursoRepository();
  final RegisterService _registerService = RegisterService();

  late final List<int> anosInicio;
  late final List<int> anosFim;

  @override
  void initState() {
    super.initState();
    _fetchCursos();

    final anoAtual = DateTime.now().year;
    anosInicio = List.generate(6, (i) => anoAtual - 5 + i);
    anosFim = List.generate(7, (i) => anoAtual + i);
  }

  Future<void> _fetchCursos() async {
    final cursos = await _cursoRepo.getCursosPorInstituicao(
      idInstituicao: widget.instituicaoId,
    );
    setState(() => _cursos = cursos);
  }

  Future<void> _register() async {
    setState(() {
      _loading = true;
      _errorMessage = null;
    });

    try {
      await _registerService.register(
        name: _nameController.text,
        email: _emailController.text,
        password: _passwordController.text,
        cursoId: _selectedCurso!,
        anoInicio: _selectedAnoInicio!,
        anoFim: _selectedAnoFim!,
        instituicaoId: widget.instituicaoId,
        passwordConfirmation: _confirmPasswordController.text,
      );

      if (!mounted) return;

      TopSnackBar.show(
        context,
        'Cadastro realizado! Verifique seu e-mail para acessar o SAM.',
      );
      Navigator.pushReplacementNamed(context, '/login');
      
    } catch (e) {
      setState(() => _errorMessage = e.toString());
    } finally {
      setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (_errorMessage != null) {
        ScaffoldMessenger.of(context).clearSnackBars();
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(
              _errorMessage!,
              style: const TextStyle(color: Colors.white),
              textAlign: TextAlign.center,
            ),
            backgroundColor: Colors.red[700],
          ),
        );
        _errorMessage = null;
      }
    });

    return Scaffold(
      resizeToAvoidBottomInset: true,
      body: CustomScrollView(
        slivers: [
          SliverAppBar(
            pinned: false,
            floating: false,
            snap: false,
            title: const Text(''),
            centerTitle: true,
            surfaceTintColor: Theme.of(context).scaffoldBackgroundColor,
            backgroundColor: Theme.of(context).scaffoldBackgroundColor,
          ),
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 32),
              child: Form(
                key: _formKey,
                child: Column(
                  children: [
                    const Column(
                      children: [
                        Text('SAM', style: TextStyle(fontSize: 80)),
                        Text('Social Academic Media'),
                      ],
                    ),
                    const SizedBox(height: 62),
                    const Text('Crie sua conta', style: TextStyle(fontSize: 32)),
                    const SizedBox(height: 16),
                    Center(child: Text(widget.nomeInstituicao.toUpperCase(), style: TextStyle(fontSize: 12),)),
                    const SizedBox(height: 45),
                    TextFormField(
                      controller: _nameController,
                      decoration: const InputDecoration(labelText: 'Nome completo'),
                      validator: (value) {
                        if (value == null || value.isEmpty) return 'Digite seu nome';
                        return null;
                      },
                    ),
                    const SizedBox(height: 16),
                    TextFormField(
                      controller: _emailController,
                      decoration: InputDecoration(
                        labelText:
                            'Email institucional (${widget.dominioInstituicao})',
                      ),
                      validator: (value) {
                        if (value == null || value.isEmpty) return 'Digite seu email';
                        if (!value.endsWith(widget.dominioInstituicao)) {
                          return 'Email deve ser do domínio ${widget.dominioInstituicao}';
                        }
                        return null;
                      },
                    ),
                    const SizedBox(height: 16),
                    DropdownButtonFormField<int>(
                      dropdownColor: Theme.of(context).scaffoldBackgroundColor,
                      value: _selectedCurso,
                      items: _cursos
                          .map(
                            (c) => DropdownMenuItem(
                              value: c.id,
                              child: Text(c.nomeCurso),
                            ),
                          )
                          .toList(),
                      onChanged: (val) => setState(() => _selectedCurso = val),
                      decoration: const InputDecoration(labelText: 'Curso'),
                      validator: (value) {
                        if (value == null) return 'Selecione um curso';
                        return null;
                      },
                    ),
                    const SizedBox(height: 16),
                    DropdownButtonFormField<int>(
                      dropdownColor: Theme.of(context).scaffoldBackgroundColor,
                      value: _selectedAnoInicio,
                      items: anosInicio
                          .map(
                            (ano) =>
                                DropdownMenuItem(value: ano, child: Text('$ano')),
                          )
                          .toList(),
                      onChanged: (val) => setState(() => _selectedAnoInicio = val),
                      decoration: const InputDecoration(labelText: 'Ano de início'),
                      validator: (value) {
                        if (value == null) return 'Selecione o ano de início';
                        return null;
                      },
                    ),
                    const SizedBox(height: 16),
                    DropdownButtonFormField<int>(
                      dropdownColor: Theme.of(context).scaffoldBackgroundColor,
                      value: _selectedAnoFim,
                      items: anosFim
                          .map(
                            (ano) =>
                                DropdownMenuItem(value: ano, child: Text('$ano')),
                          )
                          .toList(),
                      onChanged: (val) => setState(() => _selectedAnoFim = val),
                      decoration: const InputDecoration(labelText: 'Ano de fim'),
                      validator: (value) {
                        if (value == null) return 'Selecione o ano de fim';
                        return null;
                      },
                    ),
                    const SizedBox(height: 16),
                    TextFormField(
                      controller: _passwordController,
                      obscureText: true,
                      decoration: const InputDecoration(labelText: 'Senha'),
                      validator: (value) {
                        if (value == null || value.isEmpty) return 'Digite sua senha';
                        if (value.length < 6) return 'Senha deve ter ao menos 6 caracteres';
                        return null;
                      },
                    ),
                    const SizedBox(height: 16),
                    TextFormField(
                      controller: _confirmPasswordController,
                      obscureText: true,
                      decoration: const InputDecoration(labelText: 'Confirmar senha'),
                      validator: (value) {
                        if (value == null || value.isEmpty) return 'Confirme sua senha';
                        if (value != _passwordController.text) return 'As senhas não conferem';
                        return null;
                      },
                    ),
                    const SizedBox(height: 30),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: _loading
                            ? null
                            : () {
                                if (_formKey.currentState!.validate()) {
                                  _register();
                                }
                              },
                        child: _loading
                            ? const SizedBox(
                                height: 16,
                                width: 16,
                                child: CircularProgressIndicator(
                                  strokeWidth: 2,
                                  color: Colors.white,
                                ),
                              )
                            : const Text('Cadastrar'),
                      ),
                    ),
                    const SizedBox(height: 30),
                  ],
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}

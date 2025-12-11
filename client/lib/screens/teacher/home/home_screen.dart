import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:halqati/widgets/appbar/appbar_with_logo.dart';
import 'package:halqati/widgets/lists/halaqat_list.dart';
import 'package:halqati/widgets/buttons/floating_button_icon.dart';
import 'package:halqati/provider/get_classes_provider.dart';
import 'package:halqati/main.dart';

class HomeScreen extends ConsumerStatefulWidget{
  const HomeScreen({super.key});

  @override
  ConsumerState<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends ConsumerState<HomeScreen> with RouteAware{

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    routeObserver.subscribe(this, ModalRoute.of(context)! as PageRoute);
  }

  @override
  void dispose() {
    routeObserver.unsubscribe(this);
    super.dispose();
  }

  @override
  void didPopNext() {
    ref.refresh(getClassesProvider);
  }

  @override
  Widget build(BuildContext context) {
    var classesAsync = ref.watch(getClassesProvider);
    final double screenHeight = MediaQuery.of(context).size.height;
    final double appBarHeight = kToolbarHeight;

    return Scaffold(
      appBar: AppbarWithLogo(text: "home_screen.halaqati".tr()),
      floatingActionButton: FloatingButtonIcon(
        onPressed: (){
          Navigator.of(context).pushNamed("/add_halaqah_screen");
        }, text:'home_screen.create'.tr()
      ),
      body: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 30,vertical: 20),
        child: classesAsync.when(
          loading: () => const Center(child: CircularProgressIndicator()),
          error: (e, stack) => Center(
            child: Text(
              "Error loading halaqat",
              style: Theme.of(context).textTheme.bodyLarge,
            ),
          ),
          data: (halaqat){
            if(halaqat.isEmpty){
              return RefreshIndicator(
                onRefresh: () async {
                  classesAsync = ref.refresh(getClassesProvider);
                  await Future.delayed(Duration(seconds: 1));
                },
                child: ListView(
                  children: [
                    Padding(
                      padding: EdgeInsetsGeometry.fromLTRB(0,(screenHeight/2)-appBarHeight,0,0),
                      child: Center(
                        child: Text(
                          "home_screen.no_halaqat".tr(),
                          style: Theme.of(context).textTheme.bodyLarge,
                        ),
                      )
                    ),
                  ],
                ),
              );
            }
            return RefreshIndicator(
              onRefresh: ()async{
                ref.refresh(getClassesProvider);
                // await Future.delayed(Duration(seconds: 1));
              },
              child: ListView.builder(
                itemCount: halaqat.length,
                physics: const AlwaysScrollableScrollPhysics(),
                itemBuilder: (context, index) {
                  return HalaqatList(
                    onTap: () {
                      Navigator.of(context).pushNamed(
                        '/halaqah_bottom_bar',
                          arguments: halaqat[index]
                      );
                    },
                    title: halaqat[index].name!,
                    studentNumber: halaqat[index].studentCount,
                  );
                },
              ),
            );
          },
        )
      ),
    );
  }
}